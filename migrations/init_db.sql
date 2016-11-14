CREATE SEQUENCE "public"."Movies_id_seq"
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;


CREATE SEQUENCE "public"."M_genre_genre_id_seq"
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE OR REPLACE FUNCTION public.post_rating_update_func()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
BEGIN
  IF NEW.post_rating is distinct from OLD.post_rating and old.date_watched is null and new.date_watched is not distinct from old.date_watched THEN
    NEW.date_watched := current_date;
  END IF;
  RETURN NEW;
END$function$;

COMMENT ON FUNCTION "public"."post_rating_update_func"() IS NULL;


CREATE TABLE "public"."m_genre" ( 
	"genre_id" Integer DEFAULT nextval('"M_genre_genre_id_seq"'::regclass) NOT NULL,
	"title" Character Varying( 100 ) NOT NULL,
	PRIMARY KEY ( "genre_id" ) );
 
 CREATE TABLE "public"."movies" ( 
	"id" Integer DEFAULT nextval('"Movies_id_seq"'::regclass) NOT NULL,
	"title" Character Varying( 100 ) NOT NULL,
	"theater_release" Date,
	"dvd_release" Date,
	"date_watched" Date,
	"pre_rating" SmallInt,
	"post_rating" SmallInt,
	"genre_id" Integer,
	"active" Boolean DEFAULT true NOT NULL,
	PRIMARY KEY ( "id" ),
	CONSTRAINT "post_rating_range" CHECK((post_rating >= 0) AND (post_rating <= 100)),
	CONSTRAINT "pre_rating_range" CHECK((pre_rating >= 0) AND (pre_rating <= 100)) );
 
CREATE TRIGGER trigger1 BEFORE UPDATE ON "public"."movies" FOR EACH ROW EXECUTE PROCEDURE post_rating_update_func();

