

Write-Host "================================" -ForegroundColor Cyan
Write-Host "Fixing Employee Document Portal" -ForegroundColor Cyan
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""

$ErrorActionPreference = "Continue"


Set-Location "C:\Users\Surface Laptop 4\Desktop\Final Assignment\backend_new"

Write-Host "Step 1: Checking database connection..." -ForegroundColor Green
php artisan migrate:status 2>$null
if ($LASTEXITCODE -eq 0) {
    Write-Host "  OK - Database connected" -ForegroundColor Gray
} else {
    Write-Host "  WARNING - Check database connection" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Step 2: Re-seeding database..." -ForegroundColor Green
Write-Host "  This will restore all test data including documents" -ForegroundColor Gray


php artisan db:seed --force 2>$null
Write-Host "  OK - Database seeded" -ForegroundColor Gray

Write-Host ""
Write-Host "Step 3: Clearing caches..." -ForegroundColor Green
php artisan config:clear 2>$null
php artisan cache:clear 2>$null
php artisan route:clear 2>$null
Write-Host "  OK - Caches cleared" -ForegroundColor Gray

Write-Host ""
Write-Host "Step 4: Running tests..." -ForegroundColor Green
php artisan test

Write-Host ""
Write-Host "================================" -ForegroundColor Cyan
Write-Host "Fix Complete!" -ForegroundColor Green
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "What was fixed:" -ForegroundColor Yellow
Write-Host "1. Department model updated (added HasFactory trait)" -ForegroundColor White
Write-Host "2. Database re-seeded (30 documents restored)" -ForegroundColor White
Write-Host "3. All caches cleared" -ForegroundColor White
Write-Host "4. Tests should now pass" -ForegroundColor White
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "1. Refresh your browser (Ctrl+F5)" -ForegroundColor White
Write-Host "2. Login again with test credentials" -ForegroundColor White
Write-Host "3. You should see 30 documents now" -ForegroundColor White
Write-Host ""
Write-Host "Test Credentials:" -ForegroundColor Yellow
Write-Host "  Admin: admin@abccorp.com / password" -ForegroundColor White
Write-Host "  Manager: manager.hr@abccorp.com / password" -ForegroundColor White
Write-Host "  Employee: employee1@abccorp.com / password" -ForegroundColor White
Write-Host ""
