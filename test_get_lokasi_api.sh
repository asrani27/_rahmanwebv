#!/bin/bash

# Test Script untuk API Get Lokasi Absensi
# Script ini untuk testing endpoint /api/v1/presensi/lokasi

BASE_URL="http://localhost:8000/api/v1"

echo "=========================================="
echo "Testing API Get Lokasi Absensi Pegawai"
echo "=========================================="
echo ""

# Test 1: Get Lokasi dengan username yang valid
echo "Test 1: Get Lokasi dengan username yang valid"
echo "----------------------------------------"
curl -X GET "${BASE_URL}/presensi/lokasi?username=6371030807720012" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  | jq '.'
echo ""
echo ""

# Test 2: Get Lokasi dengan username yang tidak ditemukan
echo "Test 2: Get Lokasi dengan username yang tidak ditemukan"
echo "-------------------------------------------------------"
curl -X GET "${BASE_URL}/presensi/lokasi?username=invalid_user" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  | jq '.'
echo ""
echo ""

# Test 3: Get Lokasi tanpa parameter username (validation error)
echo "Test 3: Get Lokasi tanpa parameter username (validation error)"
echo "--------------------------------------------------------------"
curl -X GET "${BASE_URL}/presensi/lokasi" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  | jq '.'
echo ""
echo ""

echo "=========================================="
echo "Testing Selesai"
echo "=========================================="