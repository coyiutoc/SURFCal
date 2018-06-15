/* populating tables */

INSERT INTO Calendars VALUES 
    (null, 'mclovin calendar', 'justins personal calendar'),
    (null, 'Katies Calendar', 'mock calendar for sales demo'),
    (null, 'valentinas Calendar', 'mock calendar for sales demo'),
    (null, 'borts calendar', 'borts personal calendar'),
    (null, 'johns calendar', 'johns personal calendar');

INSERT INTO ItemType VALUES
    ('event'),
    ('reminder'),
    ('note'),
    ('task');

INSERT INTO Accounts VALUES
    (null, 'mcLovin123', 'mcLovin123@gmail.com', '7c6a180b36896a0a8c02787eeafb0e4c',
        null, 'mcLovin', '1990-04-15', '208.159.243.92', 50086912, '2018-05-30', FALSE),
    (null, 'coolcatz', 'coolcat@aol.com', '849667e75fd3e18d98f731f87caeaa43',
        null, 'Katie Lannone', '1994-12-18', '80.254.202.72', 50086913, '2018-06-01', FALSE),
    (null, 'valentinarossi', 'valentinarossi@gmail.com', 'e5d910a90d8672b7cb63ef4087ab3146f45064',
        null, 'Valentina Rossi', '1988-07-01', '67.131.190.8', 50086909, '2018-05-24', FALSE),
    (null, 'bort', 'bort@aol.com', '123457e75fg3e18d23f731f87cacdd94',
        null, 'Bort Simpson', '1994-10-29', '80.432.202.71', 50086911, '2018-06-01', FALSE),
    (null, 'johndoe', 'johndoe@gmail.com', 'a5d110b90d8673a7cd63ef4087ds3146g35023',
        null, 'John Doe', '1992-03-23', '67.132.191.9', 50086910, '2018-05-25', FALSE);


INSERT INTO EmailCodes VALUES
    (1, '45S6Y'),
    (2, '25S6Y'),
    (3, '45T6Y'),
    (4, '42U2U'),
    (5, '30J3I');

INSERT INTO Contacts VALUES
    (23008910, 1, 'Josephine Adimari'),
    (23008911, 1, 'Andrea Iannone'),
    (23008912, 1, 'Marc Marquez'),
    (23008913, 2, 'Brock Lesnar'),
    (23008914, 2, 'Stephen Thompson'),
    (23008915, 2, 'Fedor Emilionenko'),
    (23008916, 2, 'Jose Aldo'),
    (23008917, 3, 'Conor Mcgregor'),
    (23008918, 3, 'Alistar Overeem'),
    (23008919, 3, 'Dominic Cruz'),
    (23008920, 4, 'Cody Garbrandt'),
    (23008921, 4, 'Max Holloway'),
    (23008922, 4, 'Brendan Schaub'),
    (23008923, 5, 'Bobby Lee'),
    (23008924, 5, 'Theo Von'),
    (23008925, 5, 'Chris Delia');

INSERT INTO ContactAddresses VALUES
    (23008910, '1234 Sesame Street', 'Port Coquitlam', 'British Columbia', 'Canada', 'V3B1G3'),
    (23008911, '1700 Hampton Drive', 'Coquitlam', 'British Columbia', 'Canada', 'V3E3C8'),
    (23008912, '8909 Alaska Way', 'Burnaby', 'British Columbia', 'Canada', 'V5T6S9'),
    (23008913, '123 Fake st', 'Vancouver', 'British Columbia', 'Canada', 'V4N1N4'),
    (23008914, '2636 Oliver Crescent', 'Vancouver', 'British Columbia', 'Canada', 'V6J3T2'),
    (23008915, '111 Iona Drive', 'Surrey', 'British Columbia', 'Canada', 'V4TJ32'),
    (23008916, '157 Oliver Drive', 'Burnaby', 'British Columbia', 'Canada', 'V3HG2T'),
    (23008917, '999 Fire Lane', 'Coquitlam', 'British Columbia', 'Canada', 'V0TR9H'),
    (23008918, '222 Sudden Valley', 'Vancouver', 'British Columbia', 'Canada', 'V4S1N5'),
    (23008919, '2045 Maple st', 'Surrey', 'British Columbia', 'Canada', 'V3E1ET'),
    (23008920, '1580 Haro st', 'Vancouver', 'British Columbia', 'Canada', 'V9ST6T'),
    (23008921, '10740 168 st', 'Vancouver', 'British Columbia', 'Canada', 'V4N1N4'),
    (23008922, '777 Dunsmuir', 'Vancouver', 'British Columbia', 'Canada', 'V4G1G4'),
    (23008923, '609 Granville st', 'Vancouver', 'British Columbia', 'Canada', 'V4M1M4'),
    (23008924, '1412 25th ave', 'Vancouver', 'British Columbia', 'Canada', 'V4S1S4'),
    (23008925, '777 Howe st', 'Surrey', 'British Columbia', 'Canada', 'V4S1N4');
    

INSERT INTO ContactEmails VALUES
    (23008910, 'valentinorossi@gmail.com'),
    (23008911, 'aiannone0@hotmail.com'),
    (23008912, 'marquezthebiker@hotmail.com'),
    (23008913, 'brocklesnar@gmail.com'),
    (23008914, 'stephenthompson@gmail.com'),
    (23008915, 'fedoremilionenko@gmail.com'),
    (23008916, 'josealdo@gmail.com'),
    (23008917, 'conormcgregor@gmail.com'),
    (23008918, 'alistarovereem@gmail.com'),
    (23008919, 'dominiccruz@gmail.com'),
    (23008920, 'codygarbrandt@gmail.com'),
    (23008921, 'maxholloway@gmail.com'),
    (23008922, 'brendanschaub@gmail.com'),
    (23008923, 'bobbylee@gmail.com'),
    (23008924, 'theovon@gmail.com'),
    (23008925, 'chrisdelia@gmail.com');

INSERT INTO ContactPhones VALUES
    (23008910, '6042348490', 'home'),
    (23008911, '7789083245', 'work'),
    (23008912, '6048899020', 'evening');
    (23008913, '6045234234', 'home'),
    (23008914, '7789325292', 'work'),
    (23008915, '6042382932', 'evening'),
    (23008916, '6045329298', 'home'),
    (23008917, '7782522352', 'work'),
    (23008918, '6043924829', 'evening'),
    (23008919, '6045203924', 'home'),
    (23008920, '7782528382', 'work'),
    (23008921, '6041124921', 'evening'),
    (23008922, '6049383293', 'home'),
    (23008923, '7781938320', 'work'),
    (23008924, '6041048193', 'evening'),
    (23008925, '6041948193', 'home');

INSERT INTO Items VALUES
    (1, 23008910, 'event1', '2018-05-25', 'sample text 1', NULL, 'event'),
    (2, 23008911, 'event2', '2018-05-21', 'sample text 2', NULL, 'event'),
    (3, 23008912, 'event3', '2018-04-05', 'sample text 3', '2018-06-06 00:00:00', 'event'),
    (4, 23008913, 'task1', '2018-05-25', 'sample text 1', NULL, 'task'),
    (5, 23008914, 'task2', '2018-05-21', 'sample text 2', NULL, 'task'),
    (6, 23008915, 'task3', '2018-04-05', 'sample text 3', '2018-04-06 00:00:00', 'task');

INSERT INTO EventItems VALUES
    (1, '2018-05-25', '2018-05-30'),
    (2, '2018-05-21', '2018-05-30'),
    (3, '2018-04-05', '2018-05-30');

INSERT INTO TaskItems VALUES
    (4, '2018-05-25', '2018-05-30'),
    (5, '2018-05-21', '2018-05-30'),
    (6, NULL, '2018-05-30');

INSERT INTO Groups VALUES
    (1, 1),
    (2, 2),
    (3, 3);

