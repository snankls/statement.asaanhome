<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-08-13 22:50:03 --> Query error: MySQL server has gone away - Invalid query: SELECT `bookings`.*, (
				SELECT bii.amount
				FROM inventory_installments bii
				WHERE bii.inventory_id = bookings.inventory_id
				ORDER BY bii.inventory_id ASC
				LIMIT 1
			) AS first_booking_amount, (
				SELECT SUM(ii.amount)
				FROM inventory_installments ii
				WHERE ii.inventory_id = bookings.inventory_id
				AND ii.date <= CURDATE()
			) as due_amount, (
				SELECT SUM(ba.amount)
				FROM booking_amounts ba
				WHERE ba.booking_id = bookings.booking_id
			) as paid_amount, (
				SELECT i.total_price
				FROM inventories i
				WHERE i.inventory_id = bookings.inventory_id
			) as total_price, `inventories`.`inventory_id` as `inventory_inventory_id`, `bookings`.`inventory_id` as `booking_inventory_id`, `projects`.`project_name`, `bookings`.`created_on` as `create_date`, `users`.`fullname` as `user_name`
FROM `bookings`
LEFT OUTER JOIN `projects` ON `projects`.`project_id` = `bookings`.`project_id`
LEFT OUTER JOIN `inventories` ON `inventories`.`project_id` = `bookings`.`project_id`
LEFT OUTER JOIN `users` ON `users`.`user_id` = `bookings`.`created_by_id`
GROUP BY `bookings`.`booking_id`
ORDER BY `bookings`.`booking_id` DESC
ERROR - 2025-08-13 22:50:03 --> DB Query Failed: SELECT `bookings`.*, (
				SELECT bii.amount
				FROM inventory_installments bii
				WHERE bii.inventory_id = bookings.inventory_id
				ORDER BY bii.inventory_id ASC
				LIMIT 1
			) AS first_booking_amount, (
				SELECT SUM(ii.amount)
				FROM inventory_installments ii
				WHERE ii.inventory_id = bookings.inventory_id
				AND ii.date <= CURDATE()
			) as due_amount, (
				SELECT SUM(ba.amount)
				FROM booking_amounts ba
				WHERE ba.booking_id = bookings.booking_id
			) as paid_amount, (
				SELECT i.total_price
				FROM inventories i
				WHERE i.inventory_id = bookings.inventory_id
			) as total_price, `inventories`.`inventory_id` as `inventory_inventory_id`, `bookings`.`inventory_id` as `booking_inventory_id`, `projects`.`project_name`, `bookings`.`created_on` as `create_date`, `users`.`fullname` as `user_name`
FROM `bookings`
LEFT OUTER JOIN `projects` ON `projects`.`project_id` = `bookings`.`project_id`
LEFT OUTER JOIN `inventories` ON `inventories`.`project_id` = `bookings`.`project_id`
LEFT OUTER JOIN `users` ON `users`.`user_id` = `bookings`.`created_by_id`
GROUP BY `bookings`.`booking_id`
ORDER BY `bookings`.`booking_id` DESC
