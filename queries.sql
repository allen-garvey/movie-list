select * from movies where date_watched is null;
select * from movies where theater_release <= CURRENT_DATE();
select * from movies where dvd_release <= CURRENT_DATE();

select * from movies where theater_release <= CURRENT_DATE() and dvd_release > CURRENT_DATE();

select * from movies where theater_release > CURRENT_DATE() and dvd_release > CURRENT_DATE();