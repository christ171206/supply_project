# Test Guide: Marketing Hub Integration

## Phase 1: Flash Sales Display

### Test Scenario 1: Flash Sale Creation & Display
1. **Log in as vendor** in `http://localhost:3000/vendor/login`
2. **Navigate to** "Soldes Éclair" in sidebar
3. **Click "Create Flash Sale"** button
4. **Fill the form:**
   - Select a category (e.g., "Ordinateurs Portables")
   - Discount percentage: 25%
   - Start date: Today
   - End date: 30 days from today
   - Click **Save**
5. **Expected Result:** Flash sale created successfully

### Test Scenario 2: Badge Display on Product Cards
1. **Go to catalog** `http://localhost:3000/produits/catalogue`
2. **Filter by category** you selected for flash sale
3. **Expected Result:** 
   - Products in that category show orange "SOLDE -25%" badge
   - Price shows strikethrough original + reduced price below
4. **Verify calculation:** Original price × (1 - 0.25) = Reduced price

### Test Scenario 3: Flash Sale on Product Detail Page  
1. **Click on a product** in the category with the flash sale
2. **Expected Result:**
   - Section "Vente Flash -25%" badge appears near the price
   - Original price is strikethrough in gray
   - Countdown timer shows expiration date
   - Price is reduced according to the percentage

---

## Phase 2: Bundles Display & Cart

### Test Scenario 4: Bundle Creation
1. **In vendor dashboard,** click **"Bundles"** in sidebar
2. **Click "Create Bundle"** button
3. **Fill the form:**
   - Name: "Tech Starter Pack"
   - Select **2-3 products** from your catalog
   - Set quantities (e.g., 1x each)
   - The bundle price should auto-calculate as 10-15% discount from total
   - Click **Save**
4. **Expected Result:** Bundle created successfully

### Test Scenario 5: Bundle Display on Product Detail Page
1. **Go to a product** that's in the bundle you created
2. **Scroll down** to "Offres groupées" section
3. **Expected Result:**
   - Bundle card displayed with:
     - Bundle name
     - Number of products (e.g., "2 produits")
     - Product list preview
     - Green badge showing savings amount (e.g., "Économisez 15 000 FCFA")
     - Bundle price vs. original total price
     - "Ajouter au panier" button

### Test Scenario 6: Add Bundle to Cart
1. **On the bundle card,** click **"Ajouter au panier"**
2. **In the quantity modal:**
   - Set quantity to 2
   - Click **"Ajouter au panier"**
3. **Expected Result:**
   - Success message appears
   - Cart badge updates (top right)
   - Page reloads with modal closed

### Test Scenario 7: View Bundle in Cart
1. **Click cart icon** (top right)
2. **Expected Result:**
   - Bundle displayed as separate line item
   - Bundle icon (🛒) shown instead of product image
   - Text shows "Tech Starter Pack" + "2 produits"
   - Price shown for quantity × bundle price
   - Can modify quantity using +/- buttons
   - Can delete using "Supprimer" button

### Test Scenario 8: Free Shipping Progress Bar
1. **In cart view,** check the right sidebar summary
2. **Expected Result:**
   - Current subtotal shown
   - Progress bar showing progress toward 100,000 FCFA free shipping threshold
   - If all items < 100,000 FCFA: Shows "X FCFA de plus" for free shipping
   - If all items >= 100,000 FCFA: Shows "✓ Gratuite" in green
   - Total automatically updates to include shipping

### Test Scenario 9: Product + Bundle Mixed Cart
1. **Add a regular product** to cart (click from catalog)
2. **Add a bundle** (from product detail page)
3. **Expected Result:**
   - Both displayed in cart
   - Bundle item with bundle icon
   - Product item with product image
   - Separate line items
   - List below shows combined count
   - Total includes both product and bundle

---

## Phase 3: Checkout Integration

### Test Scenario 10: Checkout with Bundle
1. **In cart with bundle + product,** click **"Commander"**
2. **Go through checkout** (payment simulation)
3. **Expected Result:**
   - Bundle price correctly included in total
   - Free shipping applied if >= 100,000 FCFA
   - Order confirmation shows both product and bundle items

---

## Database Verification

Run these commands to verify database integrity:

```bash
# Check panier_items has bundle_id column
php artisan tinker
> \DB::table('panier_items')->columns(false)

# Check bundles table structure
> \DB::table('bundles')->columns(false)

# Check flash_sales table structure 
> \DB::table('flash_sales')->columns(false)

# Count active flash sales
> \App\Models\FlashSale::actif()->count()

# Count active bundles
> \App\Models\Bundle::actif()->count()
```

---

## Troubleshooting

### Flash Sale badges not showing?
1. Check that flash sale start date <= today and end date >= today
2. Check that flash sale `statut` = 'actif' and `archive` = false
3. Verify product has a category assigned
4. Clear browser cache (Ctrl+Shift+Delete)

### Bundle not appearing on product detail?
1. Check bundle `statut` = 'actif'
2. Verify product is in the bundle (check pivot table)
3. Check bundle dates are within valid range

### Cart not showing bundle?
1. Check panier_items table has `bundle_id` column
2. Verify bundle_id is set when adding (not NULL for bundles)
3. Check Laravel session/DB panier is persisting data

### Free shipping bar not showing?
1. Verify panier view shows items
2. Check that threshold logic: `$total >= 100000`
3. Verify progress calculation: `($total / 100000) * 100`

---

## Success Criteria

✅ Flash sale badges appear on product cards in category
✅ Flash sale pricing shown on product detail pages
✅ Bundle cards displayed on product detail pages
✅ Bundle successfully added to cart
✅ Bundle displayed in cart view
✅ Product + bundle mixed cart works
✅ Free shipping progress bar updates
✅ Checkout totals include bundles
✅ Order confirmation shows bundle items

---

**Last Updated:** March 16, 2026
**Implementation Status:** Phase 1 (Display) & Phase 2 (Cart) Complete
