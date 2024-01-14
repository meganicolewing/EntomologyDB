SELECT concat(enthusiast_first_name, " ", enthusiast_last_name) as 'Name', enthusiast_email_address as 'Email', 
enthusiast_phone_number as 'Phone', enthusiast_street_address as 'Street Address', enthusiast_city as "City", enthusiast_region as "Region", enthusiast_zipcode as "Postal Code", 
enthusiast_country as "country", enthusiast_id
  FROM enthusiasts
  WHERE (enthusiast_id = ?);