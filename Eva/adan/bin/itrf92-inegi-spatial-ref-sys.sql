--
-- GenesisPHP - Proyección ITRF92 usada por INEGI
--
-- http://spatialreference.org/ref/sr-org/7999/
--

INSERT into spatial_ref_sys
    (srid, auth_name, auth_srid, proj4text,
    srtext)
VALUES
    (97999, 'sr-org', 7999, '',
    'PROJCS["MEXICO_ITRF_1992_LCC",GEOGCS["ITRF_1992",DATUM["D_ITRF_1992",SPHEROID["GRS_1980",6378137.0,298.257222101]],PRIMEM["Greenwich",0.0],UNIT["Degree",0.0174532925199433]],PROJECTION["Lambert_Conformal_Conic"],PARAMETER["False_Easting",2500000.0],PARAMETER["False_Northing",0.0],PARAMETER["Central_Meridian",-102.0],PARAMETER["Standard_Parallel_1",17.5],PARAMETER["Standard_Parallel_2",29.5],PARAMETER["Latitude_Of_Origin",12.0],UNIT["Meter",1.0],AUTHORITY["INEGI","200007"]]');

