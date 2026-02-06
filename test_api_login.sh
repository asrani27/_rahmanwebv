#!/bin/bash

# API Login Test Script
# This script tests the pegawai login API endpoints

# Set your base URL here
BASE_URL="http://localhost:8000/api/v1"

echo "=========================================="
echo "API Login Test Script"
echo "=========================================="
echo ""

# Color codes for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print test results
print_result() {
    if [ $1 -eq 0 ]; then
        echo -e "${GREEN}✓ PASSED${NC}: $2"
    else
        echo -e "${RED}✗ FAILED${NC}: $2"
    fi
}

# Test 1: Login with valid credentials
echo -e "${YELLOW}Test 1: Login with valid credentials${NC}"
echo "Endpoint: POST ${BASE_URL}/pegawai/login"
echo ""

read -p "Enter username: " USERNAME
read -sp "Enter password: " PASSWORD
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
    # Extract username from response for next tests
    TEST_USERNAME=$(echo "$BODY" | python3 -c "import sys, json; print(json.load(sys.stdin)['data']['user']['username'])" 2>/dev/null)
else
    print_result 1 "Login failed with status code $HTTP_CODE"
    exit 1
fi

echo ""
echo "=========================================="

# Test 2: Get pegawai profile
echo -e "${YELLOW}Test 2: Get pegawai profile${NC}"
echo "Endpoint: GET ${BASE_URL}/pegawai/profile?username=${TEST_USERNAME}"
echo ""

RESPONSE=$(curl -s -w "\n%{http_code}" -X GET "${BASE_URL}/pegawai/profile?username=${TEST_USERNAME}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json")

HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
BODY=$(echo "$RESPONSE" | sed '$d')

echo "Response Status Code: $HTTP_CODE"
echo "Response Body:"
echo "$BODY" | python3 -m json.tool 2>/dev/null || echo "$BODY"
echo ""

if [ "$HTTP_CODE" = "200" ]; then
    print_result 0 "Profile retrieval successful"
else
    print_result 1 "Profile retrieval failed with status code $HTTP_CODE"
fi

echo ""
echo "=========================================="

# Test 3: Login with invalid credentials
echo -e "${YELLOW}Test 3: Login with invalid credentials${NC}"
echo "Endpoint: POST ${BASE_URL}/pegawai/login"
echo ""

RESPONSE=$(curl -s -w "\n%{http_code}" -X POST "${BASE_URL}/pegawai/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"username\": \"invalid_user\",
    \"password\": \"wrong_password\"
  }")

HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
BODY=$(echo "$RESPONSE" | sed '$d')

echo "Response Status Code: $HTTP_CODE"
echo "Response Body:"
echo "$BODY" | python3 -m json.tool 2>/dev/null || echo "$BODY"
echo ""

if [ "$HTTP_CODE" = "401" ]; then
    print_result 0 "Invalid credentials properly rejected"
else
    print_result 1 "Expected 401, got $HTTP_CODE"
fi

echo ""
echo "=========================================="

# Test 4: Logout
echo -e "${YELLOW}Test 4: Logout pegawai${NC}"
echo "Endpoint: POST ${BASE_URL}/pegawai/logout"
echo ""

RESPONSE=$(curl -s -w "\n%{http_code}" -X POST "${BASE_URL}/pegawai/logout" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json")

HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
BODY=$(echo "$RESPONSE" | sed '$d')

echo "Response Status Code: $HTTP_CODE"
echo "Response Body:"
echo "$BODY" | python3 -m json.tool 2>/dev/null || echo "$BODY"
echo ""

if [ "$HTTP_CODE" = "200" ]; then
    print_result 0 "Logout successful"
else
    print_result 1 "Logout failed with status code $HTTP_CODE"
fi

echo ""
echo "=========================================="
echo "All tests completed!"
echo "=========================================="