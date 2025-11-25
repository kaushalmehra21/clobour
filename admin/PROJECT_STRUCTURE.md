# Society Management System - Project Structure

## Folder Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── ResidentController.php
│   │   │   ├── UnitController.php
│   │   │   ├── BillingController.php
│   │   │   ├── PaymentController.php
│   │   │   ├── ExpenseController.php
│   │   │   ├── ComplaintController.php
│   │   │   ├── VisitorController.php
│   │   │   ├── AmenityController.php
│   │   │   ├── NoticeController.php
│   │   │   └── ReportController.php
│   │   └── AuthController.php
│   └── Middleware/
├── Models/
│   ├── Unit.php
│   ├── Resident.php
│   ├── Bill.php
│   ├── Payment.php
│   ├── Expense.php
│   ├── Complaint.php
│   ├── Visitor.php
│   ├── Amenity.php
│   ├── Notice.php
│   └── SocietySetting.php
├── Repositories/
│   ├── BaseRepository.php
│   ├── ResidentRepository.php
│   ├── UnitRepository.php
│   └── ...
├── Services/
│   ├── BillingService.php
│   ├── PaymentService.php
│   ├── NotificationService.php
│   └── ...
└── Modules/
    ├── Residents/
    ├── Billing/
    ├── Complaints/
    └── ...

resources/views/
├── admin/
│   ├── residents/
│   ├── units/
│   ├── billing/
│   ├── expenses/
│   ├── complaints/
│   ├── visitors/
│   ├── amenities/
│   ├── notices/
│   └── reports/
```

## Database Schema Overview

### Core Tables
1. **units** - Flat/Unit information
2. **residents** - Resident details (owners/tenants)
3. **monthly_bills** - Generated maintenance bills
4. **payments** - Payment records
5. **expenses** - Society expenses
6. **complaints** - Resident complaints
7. **visitors** - Visitor management
8. **amenities** - Facility booking
9. **notices** - Digital notice board

## Implementation Priority

### Phase 1: Core Setup ✅
- [x] Project structure
- [x] Migrations created
- [ ] Migration schemas populated
- [ ] Models created
- [ ] Repository pattern setup

### Phase 2: Residents & Units
- [ ] Unit CRUD
- [ ] Resident CRUD
- [ ] Move-in/out tracking

### Phase 3: Billing & Payments
- [ ] Charge management
- [ ] Bill generation
- [ ] Payment gateway integration

### Phase 4: Other Modules
- [ ] Expenses
- [ ] Complaints
- [ ] Visitors
- [ ] Amenities
- [ ] Notices
- [ ] Reports

