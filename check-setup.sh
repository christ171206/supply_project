#!/bin/bash
# Supply Marketplace - Installation & Configuration Verification Script
# This script validates that all systems are properly configured

echo "=================================="
echo "🚀 SUPPLY MARKETPLACE SETUP CHECK"
echo "=================================="
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

PASS=0
FAIL=0

# Function to check file existence
check_file() {
    if [ -f "$1" ]; then
        echo -e "${GREEN}✅${NC} File exists: $1"
        ((PASS++))
    else
        echo -e "${RED}❌${NC} File missing: $1"
        ((FAIL++))
    fi
}

# Function to check .env variable
check_env() {
    if grep -q "^$1=" .env; then
        VALUE=$(grep "^$1=" .env | cut -d '=' -f2)
        echo -e "${GREEN}✅${NC} $1 configured"
        ((PASS++))
    else
        echo -e "${RED}❌${NC} $1 missing from .env"
        ((FAIL++))
    fi
}

echo "📋 Checking File Structure..."
echo "---"
check_file "config/broadcasting.php"
check_file "app/Services/StripePaymentService.php"
check_file "app/Services/CloudinaryImageService.php"
check_file "app/Http/Controllers/PaymentController.php"
check_file "app/Http/Controllers/CloudinaryImageController.php"
check_file "app/Http/Controllers/RealtimeNotificationController.php"
check_file "app/Events/OrderCreated.php"
check_file "app/Events/OrderStatusChanged.php"
check_file "app/Events/NewMessage.php"
check_file "app/Events/VendorApprovalStatusChanged.php"
check_file "resources/js/pusher-notifications.js"
check_file "resources/views/commandes/payment.blade.php"
check_file "resources/views/vendeur/produits/gallery.blade.php"

echo ""
echo "🔑 Checking Environment Variables..."
echo "---"
check_env "BROADCAST_DRIVER"
check_env "PUSHER_APP_ID"
check_env "PUSHER_APP_KEY"
check_env "PUSHER_APP_SECRET"
check_env "STRIPE_PUBLIC_KEY"
check_env "STRIPE_SECRET_KEY"
check_env "CLOUDINARY_CLOUD_NAME"
check_env "CLOUDINARY_API_KEY"
check_env "CLOUDINARY_API_SECRET"

echo ""
echo "📦 Checking Composer Packages..."
echo "---"
if composer show pusher/pusher-php-server > /dev/null 2>&1; then
    echo -e "${GREEN}✅${NC} pusher/pusher-php-server installed"
    ((PASS++))
else
    echo -e "${YELLOW}⚠️${NC} Consider: composer require pusher/pusher-php-server"
fi

echo ""
echo "=================================="
echo "📊 RESULTS: ${GREEN}${PASS} passed${NC} / ${RED}${FAIL} failed${NC}"
echo "=================================="

if [ $FAIL -eq 0 ]; then
    echo -e "${GREEN}✅ All systems ready!${NC}"
    exit 0
else
    echo -e "${RED}⚠️  Some items need attention${NC}"
    exit 1
fi
