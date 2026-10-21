<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Delivery;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin Account
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@fooderp.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1 (555) 019-2831',
            'status' => 'active',
        ]);

        // 2. Restaurant Staff Account
        $staff = User::create([
            'name' => 'Head Chef Marco',
            'email' => 'staff@fooderp.com',
            'password' => Hash::make('password'),
            'role' => 'restaurant_staff',
            'phone' => '+1 (555) 019-4820',
            'status' => 'active',
        ]);

        // 3. Delivery Staff Accounts
        $delivery1 = User::create([
            'name' => 'David Courier',
            'email' => 'delivery1@fooderp.com',
            'password' => Hash::make('password'),
            'role' => 'delivery_staff',
            'phone' => '+1 (555) 019-9911',
            'status' => 'active',
        ]);

        $delivery2 = User::create([
            'name' => 'Alex Rider',
            'email' => 'delivery2@fooderp.com',
            'password' => Hash::make('password'),
            'role' => 'delivery_staff',
            'phone' => '+1 (555) 019-9922',
            'status' => 'active',
        ]);

        // 4. Customer Accounts & Profiles
        $customerUser1 = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'customer1@fooderp.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+1 (555) 123-4567',
            'status' => 'active',
        ]);

        $customer1 = Customer::create([
            'user_id' => $customerUser1->id,
            'name' => $customerUser1->name,
            'email' => $customerUser1->email,
            'phone' => $customerUser1->phone,
            'address' => '742 Evergreen Terrace, Sector 4',
            'city' => 'Metropolis',
            'notes' => 'Ring doorbell on arrival',
        ]);

        $customerUser2 = User::create([
            'name' => 'Michael Chen',
            'email' => 'customer2@fooderp.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+1 (555) 987-6543',
            'status' => 'active',
        ]);

        $customer2 = Customer::create([
            'user_id' => $customerUser2->id,
            'name' => $customerUser2->name,
            'email' => $customerUser2->email,
            'phone' => $customerUser2->phone,
            'address' => '10880 Wilshire Blvd, Suite 1200',
            'city' => 'Downtown',
            'notes' => 'Leave with front security desk',
        ]);

        $customerUser3 = User::create([
            'name' => 'Emily Watson',
            'email' => 'customer3@fooderp.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+1 (555) 456-7890',
            'status' => 'active',
        ]);

        $customer3 = Customer::create([
            'user_id' => $customerUser3->id,
            'name' => $customerUser3->name,
            'email' => $customerUser3->email,
            'phone' => $customerUser3->phone,
            'address' => '42 Wallaby Way',
            'city' => 'Harbor View',
            'notes' => 'Extra napkins please',
        ]);

        // 5. Food Categories
        $catAppetizers = Category::create([
            'name' => 'Starters & Appetizers',
            'slug' => 'starters-appetizers',
            'description' => 'Crispy bites and flavorful appetizers to begin your feast.',
            'is_active' => true,
        ]);

        $catBurgers = Category::create([
            'name' => 'Gourmet Burgers',
            'slug' => 'gourmet-burgers',
            'description' => 'Juicy artisan burgers loaded with premium toppings and sauces.',
            'is_active' => true,
        ]);

        $catPizza = Category::create([
            'name' => 'Pizza & Pasta',
            'slug' => 'pizza-pasta',
            'description' => 'Authentic stone-baked pizzas and fresh handmade pasta dishes.',
            'is_active' => true,
        ]);

        $catMains = Category::create([
            'name' => 'Main Courses',
            'slug' => 'main-courses',
            'description' => 'Hearty grilled steaks, seafood platters, and house specials.',
            'is_active' => true,
        ]);

        $catDesserts = Category::create([
            'name' => 'Decadent Desserts',
            'slug' => 'decadent-desserts',
            'description' => 'Sweet treats, chocolate delights, and artisan ice creams.',
            'is_active' => true,
        ]);

        $catBeverages = Category::create([
            'name' => 'Drinks & Beverages',
            'slug' => 'drinks-beverages',
            'description' => 'Refreshing mocktails, iced teas, coffees, and soft drinks.',
            'is_active' => true,
        ]);

        // 6. Food Menu Items
        $itemsData = [
            [
                'category_id' => $catAppetizers->id,
                'name' => 'Truffle Parmesan Fries',
                'description' => 'Golden crispy fries tossed in black truffle oil, garlic, and fresh parmesan.',
                'price' => 8.99,
                'image' => 'menu_items/truffle_fries.jpg',
                'is_available' => true,
                'is_featured' => true,
                'prep_time_minutes' => 10,
            ],
            [
                'category_id' => $catAppetizers->id,
                'name' => 'Crispy Calamari Rings',
                'description' => 'Lightly battered calamari served with zesty lemon garlic aioli.',
                'price' => 12.50,
                'image' => 'menu_items/crispy_calamari.jpg',
                'is_available' => true,
                'is_featured' => false,
                'prep_time_minutes' => 12,
            ],
            [
                'category_id' => $catBurgers->id,
                'name' => 'Double Bacon Smash Burger',
                'description' => 'Two Angus beef patties, double cheddar cheese, crispy bacon, caramelized onions, and secret sauce.',
                'price' => 14.99,
                'image' => 'menu_items/smash_burger.jpg',
                'is_available' => true,
                'is_featured' => true,
                'prep_time_minutes' => 15,
            ],
            [
                'category_id' => $catBurgers->id,
                'name' => 'Avocado Turkey Burger',
                'description' => 'Lean seasoned turkey patty with fresh avocado slice, swiss cheese, baby spinach, and cranberry jam.',
                'price' => 13.50,
                'image' => 'menu_items/turkey_burger.jpg',
                'is_available' => true,
                'is_featured' => false,
                'prep_time_minutes' => 15,
            ],
            [
                'category_id' => $catPizza->id,
                'name' => 'Truffle Mushroom & Mozzarella Pizza',
                'description' => 'Stone-baked sourdough base topped with wild forest mushrooms, truffle cream sauce, fresh mozzarella, and thyme.',
                'price' => 18.99,
                'image' => 'menu_items/mushroom_pizza.jpg',
                'is_available' => true,
                'is_featured' => true,
                'prep_time_minutes' => 20,
            ],
            [
                'category_id' => $catPizza->id,
                'name' => 'Classic Pepperoni Feast Pizza',
                'description' => 'Rich tomato passata sauce, double layer crispy pepperoni, and melted whole milk mozzarella.',
                'price' => 16.99,
                'image' => 'menu_items/pepperoni_pizza.jpg',
                'is_available' => true,
                'is_featured' => false,
                'prep_time_minutes' => 18,
            ],
            [
                'category_id' => $catPizza->id,
                'name' => 'Creamy Fettuccine Alfredo with Chicken',
                'description' => 'Handmade fettuccine noodles tossed in rich parmesan garlic cream sauce with grilled chicken breast.',
                'price' => 15.99,
                'image' => 'menu_items/fettuccine_alfredo.jpg',
                'is_available' => true,
                'is_featured' => false,
                'prep_time_minutes' => 15,
            ],
            [
                'category_id' => $catMains->id,
                'name' => 'Grilled Ribeye Steak (10oz)',
                'description' => 'Prime cut ribeye steak grilled to perfection, served with garlic herb butter and roasted vegetables.',
                'price' => 28.50,
                'image' => 'menu_items/ribeye_steak.jpg',
                'is_available' => true,
                'is_featured' => true,
                'prep_time_minutes' => 25,
            ],
            [
                'category_id' => $catMains->id,
                'name' => 'Pan-Seared Atlantic Salmon',
                'description' => 'Fresh Atlantic salmon fillet with lemon dill cream sauce, wild rice pilaf, and grilled asparagus.',
                'price' => 22.99,
                'image' => 'menu_items/seared_salmon.jpg',
                'is_available' => true,
                'is_featured' => false,
                'prep_time_minutes' => 20,
            ],
            [
                'category_id' => $catDesserts->id,
                'name' => 'Molten Lava Chocolate Cake',
                'description' => 'Warm chocolate cake with a gooey liquid chocolate center, served with vanilla bean ice cream.',
                'price' => 8.50,
                'image' => 'menu_items/lava_cake.jpg',
                'is_available' => true,
                'is_featured' => true,
                'prep_time_minutes' => 12,
            ],
            [
                'category_id' => $catDesserts->id,
                'name' => 'New York Style Cheesecake',
                'description' => 'Rich and creamy cheesecake with fresh strawberry reduction sauce.',
                'price' => 7.99,
                'image' => 'menu_items/cheesecake.jpg',
                'is_available' => true,
                'is_featured' => false,
                'prep_time_minutes' => 5,
            ],
            [
                'category_id' => $catBeverages->id,
                'name' => 'Fresh Berry Lemonade',
                'description' => 'Hand-pressed lemon juice infused with real raspberries, strawberries, and mint leaves.',
                'price' => 4.99,
                'image' => 'menu_items/berry_lemonade.jpg',
                'is_available' => true,
                'is_featured' => false,
                'prep_time_minutes' => 5,
            ],
            [
                'category_id' => $catBeverages->id,
                'name' => 'Iced Caramel Macchiato',
                'description' => 'Rich espresso layered with cold milk, vanilla syrup, and sweet caramel drizzle.',
                'price' => 5.50,
                'image' => 'menu_items/caramel_macchiato.jpg',
                'is_available' => true,
                'is_featured' => false,
                'prep_time_minutes' => 5,
            ],
        ];

        $createdMenuItems = [];
        foreach ($itemsData as $itemData) {
            $itemData['slug'] = Str::slug($itemData['name']);
            $createdMenuItems[] = MenuItem::create($itemData);
        }

        // 7. Seed Orders with different statuses
        // Order 1: Delivered
        $order1 = Order::create([
            'order_number' => 'ORD-' . date('Ymd') . '-A101',
            'customer_id' => $customer1->id,
            'subtotal' => 33.98,
            'tax_amount' => 1.70,
            'delivery_fee' => 3.50,
            'total_amount' => 39.18,
            'status' => 'delivered',
            'payment_status' => 'paid',
            'payment_method' => 'card',
            'delivery_address' => $customer1->address,
            'contact_phone' => $customer1->phone,
            'customer_notes' => 'Please leave at doorstep',
            'created_at' => now()->subHours(5),
        ]);

        OrderDetail::create([
            'order_id' => $order1->id,
            'menu_item_id' => $createdMenuItems[2]->id, // Double Bacon Smash Burger
            'item_name' => $createdMenuItems[2]->name,
            'unit_price' => $createdMenuItems[2]->price,
            'quantity' => 1,
            'subtotal' => 14.99,
        ]);

        OrderDetail::create([
            'order_id' => $order1->id,
            'menu_item_id' => $createdMenuItems[4]->id, // Truffle Mushroom Pizza
            'item_name' => $createdMenuItems[4]->name,
            'unit_price' => $createdMenuItems[4]->price,
            'quantity' => 1,
            'subtotal' => 18.99,
        ]);

        Delivery::create([
            'order_id' => $order1->id,
            'delivery_staff_id' => $delivery1->id,
            'status' => 'delivered',
            'assigned_at' => now()->subHours(4)->subMinutes(45),
            'picked_up_at' => now()->subHours(4)->subMinutes(30),
            'delivered_at' => now()->subHours(4),
            'delivery_notes' => 'Delivered to front porch cleanly.',
        ]);

        // Order 2: Out for Delivery
        $order2 = Order::create([
            'order_number' => 'ORD-' . date('Ymd') . '-B202',
            'customer_id' => $customer2->id,
            'subtotal' => 37.49,
            'tax_amount' => 1.87,
            'delivery_fee' => 3.50,
            'total_amount' => 42.86,
            'status' => 'out_for_delivery',
            'payment_status' => 'paid',
            'payment_method' => 'online',
            'delivery_address' => $customer2->address,
            'contact_phone' => $customer2->phone,
            'customer_notes' => 'Call when outside building entrance',
            'created_at' => now()->subMinutes(40),
        ]);

        OrderDetail::create([
            'order_id' => $order2->id,
            'menu_item_id' => $createdMenuItems[7]->id, // Ribeye Steak
            'item_name' => $createdMenuItems[7]->name,
            'unit_price' => $createdMenuItems[7]->price,
            'quantity' => 1,
            'subtotal' => 28.50,
        ]);

        OrderDetail::create([
            'order_id' => $order2->id,
            'menu_item_id' => $createdMenuItems[0]->id, // Truffle Fries
            'item_name' => $createdMenuItems[0]->name,
            'unit_price' => $createdMenuItems[0]->price,
            'quantity' => 1,
            'subtotal' => 8.99,
        ]);

        Delivery::create([
            'order_id' => $order2->id,
            'delivery_staff_id' => $delivery2->id,
            'status' => 'out_for_delivery',
            'assigned_at' => now()->subMinutes(30),
            'picked_up_at' => now()->subMinutes(15),
            'delivery_notes' => 'On courier motorcycle heading to Wilshire Blvd.',
        ]);

        // Order 3: Preparing (Kitchen Active)
        $order3 = Order::create([
            'order_number' => 'ORD-' . date('Ymd') . '-C303',
            'customer_id' => $customer3->id,
            'subtotal' => 24.49,
            'tax_amount' => 1.22,
            'delivery_fee' => 3.50,
            'total_amount' => 29.21,
            'status' => 'preparing',
            'payment_status' => 'pending',
            'payment_method' => 'cash',
            'delivery_address' => $customer3->address,
            'contact_phone' => $customer3->phone,
            'customer_notes' => 'No onions please',
            'created_at' => now()->subMinutes(20),
        ]);

        OrderDetail::create([
            'order_id' => $order3->id,
            'menu_item_id' => $createdMenuItems[6]->id, // Fettuccine Alfredo
            'item_name' => $createdMenuItems[6]->name,
            'unit_price' => $createdMenuItems[6]->price,
            'quantity' => 1,
            'subtotal' => 15.99,
        ]);

        OrderDetail::create([
            'order_id' => $order3->id,
            'menu_item_id' => $createdMenuItems[9]->id, // Lava Cake
            'item_name' => $createdMenuItems[9]->name,
            'unit_price' => $createdMenuItems[9]->price,
            'quantity' => 1,
            'subtotal' => 8.50,
        ]);

        Delivery::create([
            'order_id' => $order3->id,
            'delivery_staff_id' => $delivery1->id,
            'status' => 'assigned',
            'assigned_at' => now()->subMinutes(10),
            'delivery_notes' => 'Waiting for kitchen prep to complete.',
        ]);

        // Order 4: Pending (Newly arrived order)
        $order4 = Order::create([
            'order_number' => 'ORD-' . date('Ymd') . '-D404',
            'customer_id' => $customer1->id,
            'subtotal' => 29.98,
            'tax_amount' => 1.50,
            'delivery_fee' => 3.50,
            'total_amount' => 34.98,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => 'cash',
            'delivery_address' => $customer1->address,
            'contact_phone' => $customer1->phone,
            'customer_notes' => 'Extra crisp fries',
            'created_at' => now()->subMinutes(5),
        ]);

        OrderDetail::create([
            'order_id' => $order4->id,
            'menu_item_id' => $createdMenuItems[3]->id, // Avocado Turkey Burger
            'item_name' => $createdMenuItems[3]->name,
            'unit_price' => $createdMenuItems[3]->price,
            'quantity' => 1,
            'subtotal' => 13.50,
        ]);

        OrderDetail::create([
            'order_id' => $order4->id,
            'menu_item_id' => $createdMenuItems[5]->id, // Pepperoni Pizza
            'item_name' => $createdMenuItems[5]->name,
            'unit_price' => $createdMenuItems[5]->price,
            'quantity' => 1,
            'subtotal' => 16.48,
        ]);
    }
}
