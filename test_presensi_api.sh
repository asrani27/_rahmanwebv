#!/bin/bash

# API Presensi Test Script
# This script tests the presensi API endpoints

# Set your base URL here
BASE_URL="http://localhost:8000/api/v1"

echo "=========================================="
echo "API Presensi Test Script"
echo "=========================================="
echo ""

# Color codes for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print test results
print_result() {
    if [ $1 -eq 0 ]; then
        echo -e "${GREEN}✓ PASSED${NC}: $2"
    else
        echo -e "${RED}✗ FAILED${NC}: $2"
    fi
}

# Get credentials
echo -e "${YELLOW}Masukkan kredensial untuk testing:${NC}"
read -p "Username: " USERNAME
read -sp "Password: " PASSWORD
echo ""
read -p "Lokasi ID: " LOKASI_ID
echo ""
echo "=========================================="
echo ""

# Test 1: Login first to get username validated
echo -e "${YELLOW}Test 1: Login Pegawai${NC}"
echo "Endpoint: POST ${BASE_URL}/pegawai/login"
echo ""

RESPONSE=$(curl -s -w "\n%{http_code}" -X POST "${BASE_URL}/pegawai/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"username\": \"${USERNAME}\",
    \"password\": \"${PASSWORD}\"
  }")

HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
BODY=$(echo "$RESPONSE" | sed '$d')

echo "Response Status Code: $HTTP_CODE"
echo "Response Body:"
echo "$BODY" | python3 -m json.tool 2>/dev/null || echo "$BODY"
echo ""

if [ "$HTTP_CODE" = "200" ]; then
    print_result 0 "Login successful"
else
    print_result 1 "Login failed with status code $HTTP_CODE"
    echo -e "${RED}Cannot proceed with tests without valid login${NC}"
    exit 1
fi

echo ""
echo "=========================================="

# Test 2: Get Today Status (Before Check-in)
echo -e "${YELLOW}Test 2: Get Today Status (Before Check-in)${NC}"
echo "Endpoint: GET ${BASE_URL}/presensi/today-status?username=${USERNAME}"
echo ""

RESPONSE=$(curl -s -w "\n%{http_code}" -X GET "${BASE_URL}/presensi/today-status?username=${USERNAME}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json")

HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
BODY=$(echo "$RESPONSE" | sed '$d')

echo "Response Status Code: $HTTP_CODE"
echo "Response Body:"
echo "$BODY" | python3 -m json.tool 2>/dev/null || echo "$BODY"
echo ""

if [ "$HTTP_CODE" = "200" ]; then
    print_result 0 "Today status retrieved successfully"
else
    print_result 1 "Failed to get today status with status code $HTTP_CODE"
fi

echo ""
echo "=========================================="

# Test 3: Check-in Presensi
echo -e "${YELLOW}Test 3: Check-in Presensi${NC}"
echo "Endpoint: POST ${BASE_URL}/presensi/checkin"
echo ""
echo -e "${BLUE}Masukkan koordinat GPS (gunakan koordinat yang dekat dengan lokasi):${NC}"
read -p "Latitude: " LATITUDE
read -p "Longitude: " LONGITUDE
echo ""

RESPONSE=$(curl -s -w "\n%{http_code}" -X POST "${BASE_URL}/presensi/checkin" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"username\": \"${USERNAME}\",
    \"latitude\": ${LATITUDE},
    \"longitude\": ${LONGITUDE},
    \"lokasi_id\": ${LOKASI_ID}
  }")

HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
BODY=$(echo "$RESPONSE" | sed '$d')

echo "Response Status Code: $HTTP_CODE"
echo "Response Body:"
echo "$BODY" | python3 -m json.tool 2>/dev/null || echo "$BODY"
echo ""

if [ "$HTTP_CODE" = "200" ]; then
    print_result 0 "Check-in successful"
else
    print_result 1 "Check-in failed with status code $HTTP_CODE"
    
    # Check if it's because already checked in
    if echo "$BODY" | grep -q "sudah check-in"; then
        echo -e "${YELLOW}Note: You already checked in today, proceeding to check-out test${NC}"
    else
        echo -e "${RED}Check-in failed, cannot proceed with check-out test${NC}"
        exit 1
    fi
fi

echo ""
echo "=========================================="

# Test 4: Get Today Status (After Check-in)
echo -e "${YELLOW}Test 4: Get Today Status (After Check-in)${NC}"
echo "Endpoint: GET ${BASE_URL}/presensi/today-status?username=${USERNAME}"
echo ""

RESPONSE=$(curl -s -w "\n%{http_code}" -X GET "${BASE_URL}/presensi/today-status?username=${USERNAME}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json")

HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
BODY=$(echo "$RESPONSE" | sed '$d')

echo "Response Status Code: $HTTP_CODE"
echo "Response Body:"
echo "$BODY" | python3 -m json.tool 2>/dev/null || echo "$BODY"
echo ""

if [ "$HTTP_CODE" = "200" ]; then
    print_result 0 "Today status retrieved successfully"
else
    print_result 1 "Failed to get today status with status code $HTTP_CODE"
fi

echo ""
echo "=========================================="

# Test 5: Check-out Presensi
echo -e "${YELLOW}Test 5: Check-out Presensi${NC}"
echo "Endpoint: POST ${BASE_URL}/presensi/checkout"
echo ""
read -p "Latitude (gunakan sama atau dekat dengan check-in): " CHECKOUT_LAT
read -p "Longitude: " CHECKOUT_LON
echo ""

RESPONSE=$(curl -s -w "\n%{http_code}" -X POST "${BASE_URL}/presensi/checkout" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"username\": \"${USERNAME}\",
    \"latitude\": ${CHECKOUT_LAT},
    \"longitude\": ${CHECKOUT_LON}
  }")

HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
BODY=$(echo "$RESPONSE" | sed '$d')

echo "Response Status Code: $HTTP_CODE"
echo "Response Body:"
echo "$BODY" | python3 -m json.tool 2>/dev/null || echo "$BODY"
echo ""

if [ "$HTTP_CODE" = "200" ]; then
    print_result 0 "Check-out successful"
elif echo "$BODY" | grep -q "belum check-in"; then
    echo -e "${YELLOW}Note: You hadn't checked in yet, skipping check-out test${NC}"
    print_result 0 "Check-out test skipped"
elif echo "$BODY" | grep -q "sudah check-out"; then
    echo -e "${YELLOW}Note: You already checked out today${NC}"
    print_result 0 "Check-out test skipped"
else
    print_result 1 "Check-out failed with status code $HTTP_CODE"
fi

echo ""
echo "=========================================="

# Test 6: Get Today Status (After Check-out)
echo -e "${YELLOW}Test 6: Get Today Status (After Check-out)${NC}"
echo "Endpoint: GET ${BASE_URL}/presensi/today-status?username=${USERNAME}"
echo ""

RESPONSE=$(curl -s -w "\n%{http_code}" -X GET "${BASE_URL}/presensi/today-status?username=${USERNAME}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json")

HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
BODY=$(echo "$RESPONSE" | sed '$d')

echo "Response Status Code: $HTTP_CODE"
echo "Response Body:"
echo "$BODY" | python3 -m json.tool 2>/dev/null || echo "$BODY"
echo ""

if [ "$HTTP_CODE" = "200" ]; then
    print_result 0 "Today status retrieved successfully"
else
    print_result 1 "Failed to get today status with status code $HTTP_CODE"
fi

echo ""
echo "=========================================="

# Test 7: Get Presensi History
echo -e "${YELLOW}Test 7: Get Presensi History${NC}"
echo "Endpoint: GET ${BASE_URL}/presensi/history?username=${USERNAME}"
echo ""

RESPONSE=$(curl -s -w "\n%{http_code}" -X GET "${BASE_URL}/presensi/history?username=${USERNAME}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json")

HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
BODY=$(echo "$RESPONSE" | sed '$d')

echo "Response Status Code: $HTTP_CODE"
echo "Response Body:"
echo "$BODY" | python3 -m json.tool 2>/dev/null || echo "$BODY"
echo ""

if [ "$HTTP_CODE" = "200" ]; then
    print_result 0 "Presensi history retrieved successfully"
else
    print_result 1 "Failed to get presensi history with status code $HTTP_CODE"
fi

echo ""
echo "=========================================="

# Test 8: Get Presensi History with Month/Year Filter
echo -e "${YELLOW}Test 8: Get Presensi History with Month/Year Filter${NC}"
echo "Endpoint: GET ${BASE_URL}/presensi/history?username=${USERNAME}&month=2&year=2026"
echo ""

RESPONSE=$(curl -s -w "\n%{http_code}" -X GET "${BASE_URL}/presensi/history?username=${USERNAME}&month=2&year=2026" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json")

HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
BODY=$(echo "$RESPONSE" | sed '$d')

echo "Response Status Code: $HTTP_CODE"
echo "Response Body:"
echo "$BODY" | python3 -m json.tool 2>/dev/null || echo "$BODY"
echo ""

if [ "$HTTP_CODE" = "200" ]; then
    print_result 0 "Filtered presensi history retrieved successfully"
else
    print_result 1 "Failed to get filtered presensi history with status code $HTTP_CODE"
fi

echo ""
echo "=========================================="

# Test 9: Check-in Again (Should Fail)
echo -e "${YELLOW}Test 9: Check-in Again (Should Fail - Already Checked Out)${NC}"
echo "Endpoint: POST ${BASE_URL}/presensi/checkin"
echo ""

RESPONSE=$(curl -s -w "\n%{http_code}" -X POST "${BASE_URL}/presensi/checkin" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"username\": \"${USERNAME}\",
    \"latitude\": ${LATITUDE},
    \"longitude\": ${LONGITUDE},
    \"lokasi_id\": ${LOKASI_ID}
  }")

HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
BODY=$(echo "$RESPONSE" | sed '$d')

echo "Response Status Code: $HTTP_CODE"
echo "Response Body:"
echo "$BODY" | python3 -m json.tool 2>/dev/null || echo "$BODY"
echo ""

# Should return 400 because already checked in and out
if [ "$HTTP_CODE" = "400" ]; then
    print_result 0 "Check-in correctly rejected (already completed today)"
elif [ "$HTTP_CODE" = "200" ]; then
    print_result 1 "Check-in should have failed but succeeded"
else
    print_result 0 "Check-in correctly rejected with status code $HTTP_CODE"
fi

echo ""
echo "=========================================="

# Test 10: Check-out Again (Should Fail)
echo -e "${YELLOW}Test 10: Check-out Again (Should Fail - Already Checked Out)${NC}"
echo "Endpoint: POST ${BASE_URL}/presensi/checkout"
echo ""

RESPONSE=$(curl -s -w "\n%{http_code}" -X POST "${BASE_URL}/presensi/checkout" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"username\": \"${USERNAME}\",
    \"latitude\": ${CHECKOUT_LAT},
    \"longitude\": ${CHECKOUT_LON}
  }")

HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
BODY=$(echo "$RESPONSE" | sed '$d')

echo "Response Status Code: $HTTP_CODE"
echo "Response Body:"
echo "$BODY" | python3 -m json.tool 2>/dev/null || echo "$BODY"
echo ""

# Should return 400 because already checked out
if [ "$HTTP_CODE" = "400" ]; then
    print_result 0 "Check-out correctly rejected (already completed today)"
elif [ "$HTTP_CODE" = "200" ]; then
    print_result 1 "Check-out should have failed but succeeded"
else
    print_result 0 "Check-out correctly rejected with status code $HTTP_CODE"
fi

echo ""
echo "=========================================="
echo "All presensi API tests completed!"
echo "=========================================="