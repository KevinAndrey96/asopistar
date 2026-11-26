#!/usr/bin/env bash
# Smoke test against a running stack (make up): public site, login with CSRF,
# farmer logbook, PDF report and admin-only routes.
set -euo pipefail

BASE_URL="${BASE_URL:-http://localhost:8080}"
DEMO_PASSWORD="${DEMO_PASSWORD:-$(grep ^DEMO_PASSWORD= .env | cut -d= -f2)}"
jar="$(mktemp)"
trap 'rm -f "$jar"' EXIT

fail() {
    echo "e2e: $1" >&2
    exit 1
}

expect() {
    local body="$1" text="$2"
    grep -qF -- "$text" <<<"$body" || fail "missing \"$text\""
}

curl -fsS --retry 20 --retry-connrefused --retry-delay 2 "$BASE_URL/up" >/dev/null

home="$(curl -fsS "$BASE_URL/")"
expect "$home" "Asociación de piscicultores"
sitemap="$(curl -fsS "$BASE_URL/sitemap.xml")"
expect "$sitemap" "$BASE_URL/recetas/"

login="$(curl -fsS -c "$jar" -b "$jar" "$BASE_URL/ingresar")"
token="$(sed -n 's/.*name="_token" value="\([^"]*\)".*/\1/p' <<<"$login" | head -n1)"
[ -n "$token" ] || fail "no csrf token on the login page"

status="$(curl -sS -o /dev/null -w '%{http_code}' -c "$jar" -b "$jar" \
    --data-urlencode "_token=$token" \
    --data-urlencode "email=piscicultor@asopistar.test" \
    --data-urlencode "password=$DEMO_PASSWORD" \
    "$BASE_URL/ingresar")"
[ "$status" = "302" ] || fail "login returned $status"

panel="$(curl -fsS -b "$jar" "$BASE_URL/panel")"
expect "$panel" "Hola, Carlos"
cycle="$(grep -o '/ciclos/[0-9]*' <<<"$panel" | head -n1)"
[ -n "$cycle" ] || fail "no open cycle on the farmer dashboard"

logbook="$(curl -fsS -b "$jar" "$BASE_URL$cycle")"
expect "$logbook" "Alimentación"
expect "$logbook" "Conversión (FCR)"

content_type="$(curl -fsS -o /dev/null -w '%{content_type}' -b "$jar" "$BASE_URL$cycle/reporte")"
[ "$content_type" = "application/pdf" ] || fail "cycle report returned $content_type"

admin_status="$(curl -sS -o /dev/null -w '%{http_code}' -b "$jar" "$BASE_URL/admin")"
[ "$admin_status" = "403" ] || fail "farmer reached the admin area ($admin_status)"

echo "e2e: ok"
