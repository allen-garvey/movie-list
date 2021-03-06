SELECT title, pre_rating,  
CASE WHEN dvd_release <= CURRENT_DATE THEN 'dvd_released' ELSE CASE WHEN theater_release <= CURRENT_DATE THEN 'theater_released' ELSE 'unreleased' END END AS RELEASE, 
CASE WHEN dvd_release <= CURRENT_DATE THEN date '0001-01-01' ELSE CASE WHEN theater_release <= CURRENT_DATE THEN CASE WHEN dvd_release IS NULL AND theater_release IS NOT NULL THEN theater_release + INTERVAL '115' DAY ELSE dvd_release END ELSE theater_release END END AS release_date 
FROM "Movies" WHERE date_watched IS NULL AND post_rating IS NULL ORDER BY title;