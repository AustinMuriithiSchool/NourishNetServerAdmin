-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2024 at 10:41 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nourishnet`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(8, 'anemia'),
(4, 'Cancer'),
(1, 'Diabetes'),
(3, 'Heart Disease'),
(2, 'Hypertension'),
(7, 'Ulcers');

-- --------------------------------------------------------

--
-- Table structure for table `consultations`
--

CREATE TABLE `consultations` (
  `consultation_id` int(11) NOT NULL,
  `nutritionist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `consultation_date` date NOT NULL,
  `notes` text NOT NULL,
  `consultation_datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultations`
--

INSERT INTO `consultations` (`consultation_id`, `nutritionist_id`, `user_id`, `consultation_date`, `notes`, `consultation_datetime`) VALUES
(1, 7, 2, '2024-07-10', 'hey', '2024-07-10 22:43:00'),
(2, 6, 2, '2024-07-10', 'how are you', '2024-07-10 23:27:32'),
(3, 5, 2, '2024-07-10', 'hey', '2024-07-10 23:29:53'),
(4, 7, 4, '2024-07-10', 'hey', '2024-07-10 23:36:45'),
(5, 4, 4, '2024-07-11', 'hey', '2024-07-11 13:14:11'),
(6, 6, 4, '2024-07-11', 'hey', '2024-07-11 13:15:37'),
(7, 7, 8, '2024-07-11', 'hey', '2024-07-11 14:57:53'),
(8, 6, 8, '2024-07-11', 'hey', '2024-07-11 14:59:13'),
(9, 4, 8, '2024-07-11', 'hey', '2024-07-11 15:05:35'),
(10, 1, 2, '2024-07-14', 'hey', '2024-07-14 12:46:33');

-- --------------------------------------------------------

--
-- Table structure for table `consultation_replies`
--

CREATE TABLE `consultation_replies` (
  `reply_id` int(11) NOT NULL,
  `consultation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reply_date` datetime NOT NULL,
  `reply_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultation_replies`
--

INSERT INTO `consultation_replies` (`reply_id`, `consultation_id`, `user_id`, `reply_date`, `reply_text`) VALUES
(1, 1, 2, '2024-07-10 22:43:37', 'how are you'),
(2, 1, 7, '2024-07-10 23:08:26', 'I am good and you?'),
(3, 1, 7, '2024-07-10 23:09:12', 'I am good and you?'),
(4, 1, 2, '2024-07-10 23:14:55', 'when are you coming?'),
(5, 1, 2, '2024-07-10 23:18:01', 'just wanted to know'),
(7, 1, 7, '2024-07-10 23:22:03', 'very soon'),
(8, 1, 2, '2024-07-10 23:27:57', 'okay then'),
(9, 2, 2, '2024-07-10 23:28:11', 'are you okay'),
(10, 3, 2, '2024-07-10 23:30:06', 'how are you'),
(11, 2, 6, '2024-07-10 23:35:49', 'i am good'),
(12, 4, 4, '2024-07-10 23:37:00', 'are you good'),
(13, 4, 4, '2024-07-11 12:46:32', 'what suggestionu Have'),
(14, 5, 4, '2024-07-11 13:15:21', 'hey'),
(15, 5, 4, '2024-07-11 13:15:41', 'hey'),
(16, 6, 4, '2024-07-11 13:15:49', 'how are you'),
(17, 5, 4, '2024-07-11 13:15:54', 'hey'),
(18, 5, 4, '2024-07-11 13:15:58', 'hey'),
(19, 7, 8, '2024-07-11 14:58:22', 'how are you'),
(20, 5, 4, '2024-07-11 15:05:20', 'hey'),
(21, 9, 4, '2024-07-11 15:05:55', 'hey'),
(22, 4, 7, '2024-07-12 16:27:04', '12345'),
(23, 10, 2, '2024-07-14 12:46:40', 'how are you');

-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

CREATE TABLE `foods` (
  `food_id` int(11) NOT NULL,
  `food_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `recipe` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `foods`
--

INSERT INTO `foods` (`food_id`, `food_name`, `description`, `image_url`, `recipe`, `user_id`, `created_at`) VALUES
(12, 'Quinoa and Black Bean Salad', ' A nutritious and flavorful quinoa salad with black beans, corn, bell peppers, and a lime-cilantro dressing.', 'uploads/quino black bean salad.jpeg', '1. Cook quinoa according to package instructions and let it cool.\r\n2. In a large bowl, combine cooked quinoa, black beans, corn, diced bell peppers, red onion, and chopped cilantro.\r\n3. In a small bowl, whisk together olive oil, lime juice, cumin, salt, and pepper.\r\n4. Pour the dressing over the salad and toss to combine.\r\n5. Chill in the refrigerator for at least 30 minutes before serving.\r\n6. Serve chilled.\r\n', 1, '2024-07-03 19:09:51'),
(13, 'Grilled Salmon', 'Grilled salmon fillet with lemon and herbs', 'uploads/grilled salmon.jpeg', '1. Preheat grill to medium-high heat.\r\n2. Season salmon fillets with salt, pepper, and herbs.\r\n3. Grill salmon for 4-5 minutes per side, until cooked through.\r\n4. Squeeze fresh lemon juice over salmon before serving.\r\n', 1, '2024-07-03 19:48:51'),
(14, ' Grilled Chicken Salad', 'A healthy and delicious grilled chicken salad with fresh vegetables and a light vinaigrette dressing.', 'uploads/grilled chicken salad.jpeg', '1.Marinate chicken breast with olive oil, lemon 2.juice, garlic, salt, and pepper.\r\n3.Grill the chicken until fully cooked.\r\nI4.n a large bowl, combine mixed greens, cherry tomatoes, cucumber slices, red onion, and avocado.\r\n5.Slice the grilled chicken and place it on top of the salad.\r\n6.Drizzle with a light vinaigrette dressing and toss gently.\r\n7.Serve immediately.', 1, '2024-07-03 20:02:55'),
(15, 'Berry Smoothie', 'Refreshing smoothie packed with antioxidant-rich berries and nutrient-dense ingredients.', 'uploads/berry smoothie.jpeg', '1.Blend together a handful of mixed berries (strawberries, blueberries, raspberries).\r\n2.Add a banana for creaminess and additional nutrients.\r\n3.Incorporate a tablespoon of Greek yogurt for protein and probiotics.\r\n4.Optionally, add a teaspoon of honey for sweetness.\r\n5.Blend until smooth and serve immediately.', 1, '2024-07-03 21:16:58'),
(16, 'Greek Yogurt Parfait', 'Layered Greek yogurt with fresh berries and granola', 'uploads/Greek Yogurt Parfait.jpeg', 'In a glass or bowl, layer Greek yogurt, fresh berries (such as strawberries, blueberries, or raspberries), and granola.\r\nRepeat the layers until the glass or bowl is filled, ending with a sprinkle of granola on top.\r\nOptionally, drizzle honey or maple syrup for added sweetness.', 1, '2024-07-04 11:43:39'),
(17, 'Spinach and Mushroom Omelette', 'Fluffy omelette filled with sautéed spinach, mushrooms, and a sprinkle of cheese.', 'uploads/Spinach and Mushroom Omelette.jpeg', '1.Heat olive oil in a non-stick pan over medium heat.\r\n2.Sauté sliced mushrooms until golden brown.\r\n3.Add fresh spinach and cook until wilted.\r\n4.Beat eggs in a bowl, season with salt and pepper, and pour into the pan.\r\n5.Cook until the edges set, then sprinkle cheese over half of the omelette.\r\n6.Fold the omelette in half and cook until cheese melts.\r\n7.Serve hot with whole grain toast.', 1, '2024-07-04 11:54:08'),
(18, ' Chia Seed Pudding', 'Creamy chia seed pudding made with almond milk and topped with fresh fruits.', 'uploads/Chia Seed Pudding.jpeg', '1.Mix chia seeds with almond milk in a bowl. Let sit for 10 minutes, then stir again.\r\n2.Cover and refrigerate overnight or for at least 4 hours until thickened.\r\n3.Stir well before serving and top with fresh berries or sliced fruits.', 1, '2024-07-04 11:57:52'),
(19, 'Whole Grain Breakfast Bowl', ' Nutritious bowl of cooked whole grains (such as quinoa or oats), topped with nuts, seeds, and dried fruits.', 'uploads/Whole Grain Breakfast Bowl.jpeg', '1.Cook whole grains (quinoa or oats) according to package instructions.\r\n2.Transfer cooked grains to a bowl and top with a handful of mixed nuts (almonds, walnuts) and seeds (chia seeds, flaxseeds).\r\n3.Add dried fruits (cranberries, raisins) for sweetness.\r\n4.Optionally, drizzle with a teaspoon of honey or maple syrup.', 1, '2024-07-04 12:00:34'),
(20, ' Lentil and Vegetable Stir-Fry', 'Protein-packed lentil stir-fry with colorful vegetables and a light soy sauce dressing.', 'uploads/Lentil and Vegetable Stir-Fry.jpeg', '1.Cook lentils according to package instructions.\r\n2.Heat olive oil in a large pan over medium heat.\r\n3.Sauté diced onions, bell peppers, carrots, and zucchini until tender.\r\n4.Add cooked lentils to the pan and stir well.\r\n5.Season with low-sodium soy sauce, garlic, and ginger.\r\n6.Cook for an additional 2-3 minutes, stirring frequently.\r\n7.Serve hot, garnished with chopped green onions.', 1, '2024-07-04 12:12:11'),
(21, 'Grilled Salmon with Asparagus and Brown Rice', 'Grilled salmon fillet served with roasted asparagus and a side of brown rice.', 'uploads/Grilled Salmon with Asparagus and Brown Rice.jpeg', '1.Preheat grill to medium-high heat.\r\n2.Season salmon fillets with salt, pepper, and a drizzle of olive oil.\r\n3.Grill salmon for 4-5 minutes per side, until cooked through.\r\n4.Roast asparagus in the oven with olive oil, salt, and pepper at 400°F (200°C) for 15 minutes.\r\n5.Cook brown rice according to package instructions.\r\n6.Serve grilled salmon with roasted asparagus and brown rice.\r\n', 1, '2024-07-04 12:15:42'),
(22, ' Baked Chicken with Quinoa and Steamed Vegetables', 'Oven-baked chicken breast served with quinoa and a side of steamed vegetables.', 'uploads/Baked Chicken with Quinoa and Steamed Vegetables.jpeg', '1.Preheat oven to 375°F (190°C).\r\n2.Season chicken breasts with olive oil, salt, pepper, and your choice of herbs (e.g., rosemary, thyme).\r\n3.Place chicken on a baking sheet and bake for 25-30 minutes, until cooked through.\r\n4.Cook quinoa according to package instructions.\r\n5.Steam a mix of vegetables (e.g., broccoli, carrots, green beans) until tender.\r\n6.Serve baked chicken with quinoa and steamed vegetables on the side.', 1, '2024-07-04 12:19:33'),
(24, 'Spaghetti Squash with Tomato Basil Sauce', 'Roasted spaghetti squash served with a fresh tomato basil sauce.', 'uploads/Spaghetti Squash with Tomato Basil Sauce.jpeg', '1. Preheat oven to 375°F (190°C).\r\n2. Cut the spaghetti squash in half lengthwise and scoop out the seeds.\r\n3. Drizzle the inside of the squash with olive oil and season with salt and pepper.\r\n4. Place the squash halves cut side down on a baking sheet and roast for 40-45 minutes, until the flesh is tender.\r\n5. While the squash is roasting, heat olive oil in a pan over medium heat.\r\n6. Sauté minced garlic until fragrant, then add diced tomatoes and cook until they soften.\r\n7. Stir in fresh basil leaves, salt, and pepper. Simmer for 10-15 minutes.\r\n8. Once the squash is done, use a fork to scrape out the spaghetti-like strands into a bowl.\r\n9. Serve the spaghetti squash topped with the tomato basil sauce and a sprinkle of grated Parmesan cheese (optional).', 7, '2024-07-12 09:01:57'),
(25, 'Roasted Brussels Sprouts with Balsamic Glaze', 'Tender Brussels Sprouts Roasted To Perfection And Drizzled With A Sweet Balsamic Glaze.\r\n\r\n', 'uploads/Roasted Brussels Sprouts with Balsamic Glaze.jpeg', '1.Preheat Oven To 400°F (200°C).\r\n2. Trim The Ends Of Brussels Sprouts And Cut Them In Half.\r\n3. Toss Brussels Sprouts With Olive Oil, Salt, And Pepper On A Baking Sheet.\r\n4. Roast In the Oven For 20-25 Minutes, Shaking the Pan Halfway Through, Until Sprouts Are Golden Brown And Crispy.\r\n5. In A Small Saucepan, Simmer Balsamic Vinegar Over Medium Heat Until It Reduces By Half And Thickens Slightly (About 5-7 Minutes).\r\n6. Drizzle The Roasted Brussels Sprouts With The Balsamic Glaze Before Serving.', 7, '2024-07-14 09:37:02');

-- --------------------------------------------------------

--
-- Table structure for table `food_categories`
--

CREATE TABLE `food_categories` (
  `food_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_categories`
--

INSERT INTO `food_categories` (`food_id`, `category_id`) VALUES
(12, 2),
(13, 1),
(14, 1),
(15, 4),
(16, 4),
(17, 1),
(18, 7),
(19, 1),
(20, 1),
(21, 4),
(22, 3),
(24, 1),
(25, 7);

-- --------------------------------------------------------

--
-- Table structure for table `nutritional_values`
--

CREATE TABLE `nutritional_values` (
  `nutrition_id` int(11) NOT NULL,
  `food_id` int(11) DEFAULT NULL,
  `calories` decimal(10,2) DEFAULT NULL,
  `protein` decimal(10,2) DEFAULT NULL,
  `fat` decimal(10,2) DEFAULT NULL,
  `carbohydrates` decimal(10,2) DEFAULT NULL,
  `vitamins` varchar(255) DEFAULT NULL,
  `minerals` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nutritional_values`
--

INSERT INTO `nutritional_values` (`nutrition_id`, `food_id`, `calories`, `protein`, `fat`, `carbohydrates`, `vitamins`, `minerals`) VALUES
(12, 12, '180.00', '8.00', '5.00', '29.00', 'Vitamin C, Vitamin B6', 'Magnesium, Potassium'),
(13, 13, '280.00', '25.00', '18.00', '0.00', 'Vitamin D, Vitamin B12', 'Omega-3 fatty acids'),
(14, 14, '250.00', '30.00', '10.00', '10.00', 'Vitamin A, Vitamin C', 'Iron, Calcium'),
(15, 15, '150.00', '5.00', '2.00', '30.00', 'Vitamin C, Vitamin K', 'Potassium, Magnesium'),
(16, 16, '250.00', '15.00', '7.00', '35.00', 'Vitamin C', 'Calcium, Magnesium'),
(17, 17, '280.00', '20.00', '18.00', '5.00', 'Vitamin D, Vitamin B12', 'Iron, Potassium'),
(18, 18, '220.00', '6.00', '10.00', '25.00', 'Vitamin C', 'Calcium, Magnesium'),
(19, 19, '300.00', '10.00', '12.00', '40.00', 'Vitamin E, Vitamin B6', ' Iron, Zinc'),
(20, 20, '350.00', '18.00', '8.00', '50.00', 'Vitamin A, Vitamin C', 'Iron, Folate'),
(21, 21, '450.00', '30.00', '20.00', '40.00', 'Vitamin D, Vitamin B12', 'Omega-3 fatty acids, Magnesium'),
(22, 22, '400.00', '35.00', '10.00', '40.00', 'Vitamin A, Vitamin C', ' Iron, Potassium'),
(24, 24, '180.00', '4.00', '8.00', '24.00', 'Vitamin A, Vitamin C', ' Potassium, Calcium'),
(25, 25, '150.00', '5.00', '7.00', '7.00', ' Vitamin A, Vitamin C', 'Calcium, Magnesium');

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `rating_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `recipe_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `rating_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `rating_comment` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`rating_id`, `user_id`, `recipe_id`, `rating`, `rating_date`, `rating_comment`) VALUES
(1, 2, 1, 1, '2024-07-12 09:24:31', 'good food'),
(2, 4, 1, 5, '2024-07-10 12:42:22', ''),
(3, 4, 2, 5, '2024-07-03 09:21:03', 'very descriptive'),
(4, 4, 3, 3, '2024-07-03 10:54:09', 'good and basic'),
(0, 4, 5, 5, '2024-07-14 11:06:22', ''),
(0, 2, 5, 4, '2024-07-15 07:24:13', ''),
(0, 2, 4, 4, '2024-07-15 07:24:40', '');

-- --------------------------------------------------------

--
-- Table structure for table `recipe`
--

CREATE TABLE `recipe` (
  `recipe_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `recipe` varchar(255) NOT NULL,
  `recipe_description` text DEFAULT NULL,
  `recipe_ingredients` text DEFAULT NULL,
  `recipe_instructions` text DEFAULT NULL,
  `recipe_image` varchar(255) DEFAULT NULL,
  `recipe_tag` varchar(255) NOT NULL,
  `recipe_nutrition` varchar(500) NOT NULL,
  `recipe_suitability` varchar(500) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipe`
--

INSERT INTO `recipe` (`recipe_id`, `user_id`, `recipe`, `recipe_description`, `recipe_ingredients`, `recipe_instructions`, `recipe_image`, `recipe_tag`, `recipe_nutrition`, `recipe_suitability`, `date_created`, `date_modified`) VALUES
(1, 2, 'ugali', 'a good kenyan meal \"i guess\"', 'flour\r\nwater\r\nsome other stuff', 'simmer some water\r\nadd some flour\r\nmix the flour and water ', 'uploads/Ugali_&_Sukuma_Wiki.jpg', 'Dinner', '', '', '2024-07-10 13:27:14', '2024-07-10 13:27:14'),
(2, 2, 'Chocolate Chip Cookies', 'These classic chocolate chip cookies are crispy on the edges and chewy in the center. Perfect for satisfying your sweet tooth!', '1 cup unsalted butter, softened\r\n1 cup granulated sugar\r\n1 cup packed brown sugar\r\n2 large eggs\r\n1 teaspoon vanilla extract\r\n3 cups all-purpose flour\r\n1 teaspoon baking soda\r\n1/2 teaspoon baking powder\r\n1 teaspoon salt\r\n2 cups semisweet chocolate chips', 'Preheat your oven to 350°F (175°C).\r\nIn a large bowl, cream together the butter, granulated sugar, and brown sugar until smooth.\r\nBeat in the eggs one at a time, then stir in the vanilla extract.\r\nIn a separate bowl, combine the flour, baking soda, baking powder, and salt.\r\nGradually blend the dry ingredients into the wet mixture.\r\nStir in the chocolate chips by hand using a wooden spoon.\r\nDrop dough by rounded tablespoons onto ungreased cookie sheets.\r\nBake for about 10 minutes in the preheated oven, or until edges are nicely browned.\r\nAllow cookies to cool on baking sheet for 5 minutes before transferring to a wire rack to cool completely.', 'uploads/images.jpeg', 'Snack', 'Calories: 180\r\nTotal Fat: 9g\r\nSaturated Fat: 5g\r\nTrans Fat: 0g\r\nCholesterol: 25mg\r\nSodium: 130mg\r\nTotal Carbohydrate: 25g\r\nDietary Fiber: 1g\r\nSugars: 16g\r\nProtein: 2g', 'Vegetarians: The recipe is vegetarian-friendly as it does not contain any meat or fish products.\r\nNut Allergies: Depending on the chocolate chips used, there may be a risk of nut cross-contamination. Ensure to use nut-free chocolate chips if allergies are a concern.\r\nLactose Intolerance: The recipe contains butter, which can be substituted with a dairy-free alternative like margarine if needed.', '2024-07-10 13:27:14', '2024-07-10 13:27:14'),
(3, 4, 'Simple Plain Rice', 'This simple rice recipe provides a versatile base that can accompany a variety of dishes. It\'s easy to prepare and can be customized with herbs and spices to suit different tastes.', '1 cup white rice (long-grain or jasmine)\r\n2 cups water\r\nSalt (optional)', 'Place the rice in a fine-mesh sieve and rinse it under cold water until the water runs clear. This removes excess starch and helps prevent the rice from becoming sticky.\r\nIn a medium saucepan, bring 2 cups of water to a boil.\r\nAdd a pinch of salt if desired (optional).\r\nStir in the rinsed rice and return to a boil.\r\nOnce boiling, reduce the heat to low and cover the saucepan with a lid.\r\nLet the rice simmer for 15-20 minutes (for white rice) or according to package instructions, until the water is absorbed and the rice is tender.\r\nRemove the saucepan from heat and let it sit, covered, for 5 minutes.\r\nFluff the rice with a fork to separate the grains.', 'uploads/plain-white-rice-f7391e.jpg', 'Lunch', 'Calories: Approximately 200 kcal per serving (1 cup cooked rice)\r\nCarbohydrates: About 45 grams per serving\r\nProtein: Around 4 grams per serving\r\nFat: Approximately 0 grams per serving\r\nFiber: About 1 gram per serving', 'Gluten-free', '2024-07-10 13:27:14', '2024-07-10 13:27:14'),
(4, 2, 'Caprese Salad', 'A classic Italian salad showcasing fresh tomatoes, mozzarella cheese, and basil leaves drizzled with balsamic glaze and olive oil. It\'s a refreshing and vibrant dish, perfect for summer or as a light appetizer.', '2 large tomatoes, sliced\r\n1 ball of fresh mozzarella cheese, sliced\r\nFresh basil leaves\r\nBalsamic glaze\r\nExtra virgin olive oil\r\nSalt and pepper to taste', 'Arrange tomato slices and mozzarella cheese slices alternately on a serving platter.\r\nTuck fresh basil leaves between the tomato and mozzarella slices.\r\nDrizzle with balsamic glaze and extra virgin olive oil.\r\nSeason with salt and pepper to taste.\r\nServe immediately as a refreshing salad or appetizer.', 'uploads/Caprese Salad.jpeg', 'Lunch', 'Calories: 200\r\nProtein: 12g\r\nCarbohydrates: 5g\r\nFat: 15g\r\nFiber: 2g', 'Low carb\r\nVegetarian\r\nGluten-free', '2024-07-14 12:58:55', '2024-07-14 12:58:55'),
(5, 4, 'Avocado Toast', 'A simple and delicious breakfast or snack option made with ripe avocados spread on toasted bread, topped with a sprinkle of salt, pepper, and optional toppings. It\'s a versatile and nutritious choice perfect for any time of the day.', '2 slices of whole-grain bread\r\n1 ripe avocado\r\n1 teaspoon lemon juice\r\nSalt and pepper to taste\r\nOptional toppings: cherry tomatoes, red pepper flakes, poached egg, feta cheese, microgreens', 'Toast the slices of whole-grain bread to your desired level of crispiness.\r\nWhile the bread is toasting, cut the avocado in half, remove the pit, and scoop the flesh into a bowl.\r\nMash the avocado with a fork until smooth, then mix in the lemon juice, salt, and pepper.\r\nSpread the mashed avocado evenly onto the toasted bread slices.\r\nAdd any optional toppings such as cherry tomatoes, red pepper flakes, poached egg, feta cheese, or microgreens.\r\nServe immediately as a delicious breakfast or snack.', 'uploads/avocado toast.jpeg', 'Lunch', 'Calories: 250\r\nProtein: 6g\r\nCarbohydrates: 25g\r\nFat: 16g\r\nFiber: 7g', 'Heart Health\r\nVegan\r\nGluten-free (if using gluten-free bread)\r\nDiabetes-friendly', '2024-07-14 14:05:40', '2024-07-14 14:05:40'),
(6, 2, 'Homemade Granola', 'This homemade granola is a delicious and nutritious blend of oats, nuts, and seeds, lightly sweetened with honey and flavored with a hint of vanilla and cinnamon. Perfect for a healthy breakfast or snack, it’s easy to make and can be customized with your favorite ingredients.', '3 cups old-fashioned rolled oats\r\n1 cup nuts (such as almonds, walnuts, or pecans), roughly chopped\r\n1/2 cup seeds (such as sunflower seeds or pumpkin seeds)\r\n1/2 cup shredded coconut (optional)\r\n1/4 cup honey or maple syrup\r\n1/4 cup coconut oil or olive oil\r\n1 teaspoon vanilla extract\r\n1 teaspoon ground cinnamon\r\n1/4 teaspoon salt\r\n1/2 cup dried fruit (such as raisins, cranberries, or apricots), chopped (optional)', 'Preheat your oven to 325°F (165°C). Line a baking sheet with parchment paper.\r\nIn a large bowl, combine the oats, nuts, seeds, shredded coconut (if using), cinnamon, and salt.\r\nIn a small saucepan over low heat, melt the coconut oil (or olive oil) and honey (or maple syrup) together. Stir in the vanilla extract.\r\nPour the wet ingredients over the dry ingredients and mix well until everything is evenly coated.\r\nSpread the mixture evenly on the prepared baking sheet. Bake for 20-25 minutes, stirring halfway through, until the granola is golden brown and crisp.\r\nRemove from the oven and let the granola cool completely on the baking sheet. Once cool, stir in the dried fruit (if using).\r\nStore the granola in an airtight container at room temperature for up to two weeks.', 'uploads/slow-cooker-granola-2.jpg', 'Breakfast', 'Calories: ~250\r\nProtein: 6g\r\nCarbohydrates: 30g\r\nFat: 12g\r\nFiber: 4g\r\nSugar: 10g', 'Vegetarian: This granola is suitable for a vegetarian diet.\r\nGluten-Free: Ensure that your oats are certified gluten-free if you need a gluten-free option.\r\nDairy-Free: This recipe is dairy-free, perfect for those with lactose intolerance.\r\nVegan: Use maple syrup instead of honey to make this granola vegan-friendly.', '2024-07-15 10:06:51', '2024-07-15 10:06:51'),
(7, 2, 'Classic Crispy Bacon', 'A classic breakfast staple, crispy bacon is a favorite for its rich, savory flavor and satisfying crunch. Perfect alongside eggs, pancakes, or in a breakfast sandwich.', '8 slices of bacon (regular or thick-cut)\r\nOptional: black pepper, brown sugar, or maple syrup for seasoning', 'Preheat your oven to 400°F (200°C).\r\nLine a baking sheet with aluminum foil for easy cleanup and place a wire rack on top of the baking sheet.\r\nLay the bacon slices in a single layer on the wire rack. Make sure the slices don’t overlap for even cooking.\r\nFor a savory twist, sprinkle black pepper over the bacon slices.\r\nFor a sweet touch, sprinkle brown sugar or brush with maple syrup.\r\nPlace the baking sheet in the preheated oven.\r\nBake for 15-20 minutes, depending on the thickness of the bacon and your desired level of crispiness. Keep an eye on the bacon to prevent burning.\r\nOnce the bacon is cooked to your liking, remove it from the oven.\r\nTransfer the bacon slices to a paper towel-lined plate to drain excess fat.\r\nServe immediately.', 'uploads/Brown_Sugar_Bacon_RECIPE_081722_38635.webp', 'Breakfast', 'Calories: ~42\r\nProtein: 3g\r\nCarbohydrates: 0g\r\nFat: 3.3g\r\nFiber: 0g\r\nSodium: 194mg', 'Dietary Preferences: Suitable for low-carb and ketogenic diets.\r\nAllergies: Contains no common allergens but check for any added ingredients in packaged bacon.\r\nHealth Considerations: Due to its high sodium and fat content, bacon should be consumed in moderation, especially for those with heart disease or high blood pressure.', '2024-07-15 10:13:47', '2024-07-15 10:13:47'),
(8, 2, 'Spicy Parmesan Popcorn', 'This Spicy Parmesan Popcorn is a deliciously savory and slightly spicy snack that\'s perfect for movie nights or gatherings. It\'s quick to make and provides a satisfying crunch with a hint of heat and cheesy goodness.', '1/2 cup popcorn kernels\r\n3 tablespoons olive oil or coconut oil\r\n1/4 cup grated Parmesan cheese\r\n1/2 teaspoon smoked paprika\r\n1/4 teaspoon cayenne pepper (adjust to taste)\r\n1/2 teaspoon garlic powder\r\nSalt to taste\r\n1 tablespoon chopped fresh parsley (optional)', 'In a large pot, heat the olive oil over medium-high heat. Add a few popcorn kernels and cover the pot.\r\nOnce the kernels pop, add the remaining popcorn kernels in an even layer. Cover the pot and shake it gently to coat the kernels with oil.\r\nContinue cooking, shaking the pot occasionally, until the popping slows down to about 2 seconds between pops. Remove the pot from heat and let it sit for a minute to finish popping.\r\nTransfer the popcorn to a large bowl.\r\nSprinkle the Parmesan cheese, smoked paprika, cayenne pepper, garlic powder, and salt over the popcorn.\r\nToss well to ensure the popcorn is evenly coated with the seasonings.\r\nIf using, sprinkle the chopped fresh parsley over the seasoned popcorn.\r\nServe immediately and enjoy!', 'uploads/210407.parmesan.garlic.popcorn.updated-6703-8-500x375.jpg', 'Snack', 'Calories: 150\r\nProtein: 4g\r\nCarbohydrates: 14g\r\nFat: 9g\r\nFiber: 3g\r\nSodium: 200mg', 'Vegetarian: Yes\r\nGluten-Free: Yes (ensure the granola used is gluten-free)\r\nNut-Free: Yes\r\nDairy-Free: No (to make it dairy-free, omit the Parmesan cheese or use a dairy-free cheese alternative)\r\nVegan: No (to make it vegan, omit the Parmesan cheese or use a vegan cheese alternative)', '2024-07-15 10:21:04', '2024-07-15 10:21:04');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_tag`
--

CREATE TABLE `recipe_tag` (
  `recipe_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` varchar(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `user_type`, `profile_image`) VALUES
(1, 'austinrulez', '$2y$10$9WCIGoksQf8QQEQqkahuxeA8nXu2YGe64yOlJ9Dpn1pTKaNP4ezHe', 'austin1@gmail.com', 'nutritionist', 'profilepictures/80c93328-ae9f-4c6e-83d8-f68314b27db0.jpeg'),
(2, 'tashierulez1', '$2y$10$4jP77QZaGAR2FqcLRGb7DOO4INYh67u3sDc4XAvMNI7Jiu4pXLW3e', 'natashaorwenyo@gmail.com', 'user', 'profilepictures/black-woman-fa7e50.jpg'),
(3, 'jasonrulez1', '$2y$10$JZrpaVEpyX4yN1v1O5nqYuyj3f9/xAhkQduYJfCSwv.JcKPqWanBK', 'jason@gmail.com', 'admin', NULL),
(4, 'exoexo', '$2y$10$62ehls/EgU5nMihcZi24q.oNjz6twzVCKQObLxg1f4AECQ.mArRhO', 'excellence@gmail.com', 'user', 'profilepictures/doe.jpeg'),
(5, 'nutritionist1', '12345', 'nutri1@example.com', 'nutritionist', NULL),
(6, 'Mike', '$2y$10$0ESaEeCzv1dnuveJddX1KuWROS2ckmnD0kNBUwkd2AyQv/LJCPMjq', 'mike.ma@gmail.com', 'nutritionist', NULL),
(7, 'Doe', '$2y$10$KeW3vA5dZECkOTzRS6LOc.vzcLHRiT67Xny6wfPL5K97TzPfz.kWe', 'Doe@gmail.com', 'nutritionist', 'profilepictures/64229562-59bd-4f33-aa2d-6da0f77a9744.jpeg'),
(8, 'magot', '$2y$10$sQBN6egpYSbHtPXLrklo0O.OUA4/9bDem07U8VBK1Ya9SAy9NOG6q', 'magot@gmail.com', 'user', NULL),
(9, 'Austin', '$2y$10$YNKVsxLWsmGJWjiCGFWX4eVKWlgcYTfVLe9d3SPweZpyMXEuVzYlu', 'austin1@gmail.com', 'admin', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `consultations`
--
ALTER TABLE `consultations`
  ADD PRIMARY KEY (`consultation_id`),
  ADD KEY `nutritionist_id` (`nutritionist_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `consultation_replies`
--
ALTER TABLE `consultation_replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `consultation_id` (`consultation_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`food_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `food_categories`
--
ALTER TABLE `food_categories`
  ADD PRIMARY KEY (`food_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `nutritional_values`
--
ALTER TABLE `nutritional_values`
  ADD PRIMARY KEY (`nutrition_id`),
  ADD KEY `food_id` (`food_id`);

--
-- Indexes for table `recipe`
--
ALTER TABLE `recipe`
  ADD PRIMARY KEY (`recipe_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `consultations`
--
ALTER TABLE `consultations`
  MODIFY `consultation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `consultation_replies`
--
ALTER TABLE `consultation_replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `foods`
--
ALTER TABLE `foods`
  MODIFY `food_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `nutritional_values`
--
ALTER TABLE `nutritional_values`
  MODIFY `nutrition_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `recipe`
--
ALTER TABLE `recipe`
  MODIFY `recipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `consultations`
--
ALTER TABLE `consultations`
  ADD CONSTRAINT `fk_consultations_nutritionist` FOREIGN KEY (`nutritionist_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_consultations_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `consultation_replies`
--
ALTER TABLE `consultation_replies`
  ADD CONSTRAINT `fk_consultation_replies_consultation` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`consultation_id`),
  ADD CONSTRAINT `fk_consultation_replies_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `foods`
--
ALTER TABLE `foods`
  ADD CONSTRAINT `foods_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `food_categories`
--
ALTER TABLE `food_categories`
  ADD CONSTRAINT `food_categories_ibfk_1` FOREIGN KEY (`food_id`) REFERENCES `foods` (`food_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `food_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `nutritional_values`
--
ALTER TABLE `nutritional_values`
  ADD CONSTRAINT `nutritional_values_ibfk_1` FOREIGN KEY (`food_id`) REFERENCES `foods` (`food_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
