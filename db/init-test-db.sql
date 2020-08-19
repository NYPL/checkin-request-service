SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;


SET search_path = public, pg_catalog;

--
-- Name: checkin_request_id_seq; Type: SEQUENCE; Schema: public; Owner:
--

CREATE SEQUENCE checkin_request_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: checkin_request; Type: TABLE; Schema: public; Owner:
--

CREATE TABLE checkin_request (
    id integer DEFAULT nextval('checkin_request_id_seq'::regclass) NOT NULL,
    cancel_request_id integer,
    job_id text,
    item_barcode text,
    owning_institution_id text,
    success boolean,
    created_date text,
    updated_date text
);


--
-- Name: checkin_request_pkey; Type: CONSTRAINT; Schema: public; Owner:
--

ALTER TABLE ONLY checkin_request
    ADD CONSTRAINT checkin_request_pkey PRIMARY KEY (id);

