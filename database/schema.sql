--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- Name: seq_albums; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE seq_albums
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: seq_artists; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE seq_artists
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: seq_friends; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE seq_friends
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: seq_genres; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE seq_genres
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: seq_loans; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE seq_loans
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: seq_locations; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE seq_locations
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: seq_songs; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE seq_songs
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


SET default_tablespace = '';

--
-- Name: tbl_album_artist; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE tbl_album_artist (
    artist_id integer NOT NULL,
    album_id integer NOT NULL
);


--
-- Name: tbl_album_other_artist; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE tbl_album_other_artist (
    artist_id integer NOT NULL,
    album_id integer NOT NULL
);


--
-- Name: tbl_albums; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE tbl_albums (
    album_id integer DEFAULT nextval(('seq_albums'::text)::regclass) NOT NULL,
    album_title character varying(128) NOT NULL,
    album_year integer,
    album_rate integer,
    album_original integer,
    album_location_id integer,
    album_genre_id integer,
    album_number integer,
    album_notes text
);


--
-- Name: tbl_artists; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE tbl_artists (
    artist_id integer DEFAULT nextval(('seq_artists'::text)::regclass) NOT NULL,
    artist_name character varying(128) NOT NULL,
    artist_biography text
);


--
-- Name: tbl_friends; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE tbl_friends (
    friend_id integer DEFAULT nextval(('seq_friends'::text)::regclass) NOT NULL,
    friend_name character varying(128) NOT NULL,
    friend_email character varying(128) NOT NULL
);


--
-- Name: tbl_genres; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE tbl_genres (
    genre_id integer DEFAULT nextval(('seq_genres'::text)::regclass) NOT NULL,
    genre character varying(128) NOT NULL
);


--
-- Name: tbl_loans; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE tbl_loans (
    loan_id integer DEFAULT nextval(('seq_loans'::text)::regclass) NOT NULL,
    loan_date date,
    loan_album_id integer NOT NULL,
    loan_friend_id integer NOT NULL,
    loan_returned integer DEFAULT 0,
    loan_return_date date
);


--
-- Name: tbl_locations; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE tbl_locations (
    location_id integer DEFAULT nextval(('seq_locations'::text)::regclass) NOT NULL,
    location_desc character varying(128) NOT NULL
);


--
-- Name: tbl_songs; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE tbl_songs (
    song_id integer DEFAULT nextval(('seq_songs'::text)::regclass) NOT NULL,
    song_title character varying(128) NOT NULL,
    track_number integer,
    track_duration character varying(6),
    track_lyrics text,
    album_id integer NOT NULL
);


--
-- Name: tbl_albums_album_id_key; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tbl_albums
    ADD CONSTRAINT tbl_albums_album_id_key UNIQUE (album_id);


--
-- Name: tbl_albums_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tbl_albums
    ADD CONSTRAINT tbl_albums_pkey PRIMARY KEY (album_id);


--
-- Name: tbl_artists_artist_id_key; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tbl_artists
    ADD CONSTRAINT tbl_artists_artist_id_key UNIQUE (artist_id);


--
-- Name: tbl_artists_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tbl_artists
    ADD CONSTRAINT tbl_artists_pkey PRIMARY KEY (artist_id);


--
-- Name: tbl_friends_friend_id_key; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tbl_friends
    ADD CONSTRAINT tbl_friends_friend_id_key UNIQUE (friend_id);


--
-- Name: tbl_friends_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tbl_friends
    ADD CONSTRAINT tbl_friends_pkey PRIMARY KEY (friend_id);


--
-- Name: tbl_genres_genre_id_key; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tbl_genres
    ADD CONSTRAINT tbl_genres_genre_id_key UNIQUE (genre_id);


--
-- Name: tbl_genres_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tbl_genres
    ADD CONSTRAINT tbl_genres_pkey PRIMARY KEY (genre_id);


--
-- Name: tbl_loans_loan_id_key; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tbl_loans
    ADD CONSTRAINT tbl_loans_loan_id_key UNIQUE (loan_id);


--
-- Name: tbl_loans_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tbl_loans
    ADD CONSTRAINT tbl_loans_pkey PRIMARY KEY (loan_id);


--
-- Name: tbl_locations_location_id_key; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tbl_locations
    ADD CONSTRAINT tbl_locations_location_id_key UNIQUE (location_id);


--
-- Name: tbl_locations_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tbl_locations
    ADD CONSTRAINT tbl_locations_pkey PRIMARY KEY (location_id);


--
-- Name: tbl_songs_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tbl_songs
    ADD CONSTRAINT tbl_songs_pkey PRIMARY KEY (song_id);


--
-- Name: tbl_songs_song_id_key; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tbl_songs
    ADD CONSTRAINT tbl_songs_song_id_key UNIQUE (song_id);


--
-- Name: albums_index; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX albums_index ON tbl_albums USING btree (album_title);


--
-- Name: albums_index_id; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX albums_index_id ON tbl_albums USING btree (album_id);


--
-- Name: albums_index_location; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX albums_index_location ON tbl_albums USING btree (album_location_id);


--
-- Name: albums_index_number; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX albums_index_number ON tbl_albums USING btree (album_number);


--
-- Name: albums_index_year; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX albums_index_year ON tbl_albums USING btree (album_year);


--
-- Name: artist_index; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX artist_index ON tbl_artists USING btree (artist_name);


--
-- Name: artist_index_id; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX artist_index_id ON tbl_artists USING btree (artist_id);


--
-- Name: songs_index_album_id; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX songs_index_album_id ON tbl_songs USING btree (album_id);


--
-- Name: songs_index_song_title; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX songs_index_song_title ON tbl_songs USING btree (song_title);


--
-- Name: $1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY tbl_album_artist
    ADD CONSTRAINT "$1" FOREIGN KEY (artist_id) REFERENCES tbl_artists(artist_id);


--
-- Name: $1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY tbl_album_other_artist
    ADD CONSTRAINT "$1" FOREIGN KEY (artist_id) REFERENCES tbl_artists(artist_id);


--
-- Name: $1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY tbl_loans
    ADD CONSTRAINT "$1" FOREIGN KEY (loan_album_id) REFERENCES tbl_albums(album_id);


--
-- Name: $2; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY tbl_album_artist
    ADD CONSTRAINT "$2" FOREIGN KEY (album_id) REFERENCES tbl_albums(album_id);


--
-- Name: $2; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY tbl_album_other_artist
    ADD CONSTRAINT "$2" FOREIGN KEY (album_id) REFERENCES tbl_albums(album_id);


--
-- Name: $2; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY tbl_loans
    ADD CONSTRAINT "$2" FOREIGN KEY (loan_friend_id) REFERENCES tbl_friends(friend_id);


--
-- PostgreSQL database dump complete
--

