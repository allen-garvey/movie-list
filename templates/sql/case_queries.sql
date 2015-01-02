-- Without estimated dvd_release
SELECT title, pre_rating, theater_release, CASE WHEN dvd_release is null then (theater_release + INTERVAL '$dvd_lead_time' DAY) else dvd_release as dvd_release, CASE WHEN dvd_release <= CURRENT_DATE THEN 'dvd' ELSE CASE WHEN theater_release <= CURRENT_DATE THEN 'theater' ELSE 'unreleased' END END AS RELEASE FROM "Movies" WHERE date_watched IS NULL AND post_rating IS NULL ORDER BY title;


-- with dvd_release set for those that don't have it
SELECT title, pre_rating, theater_release, CASE WHEN dvd_release IS NULL AND theater_release IS NOT NULL THEN theater_release + INTERVAL '115' DAY ELSE dvd_release END AS dvd_release, CASE WHEN dvd_release <= CURRENT_DATE THEN 'dvd' ELSE CASE WHEN theater_release <= CURRENT_DATE THEN 'theater' ELSE 'unreleased' END END AS RELEASE FROM "Movies" WHERE date_watched IS NULL AND post_rating IS NULL ORDER BY title;