/* populating tables */

INSERT INTO Calendars VALUES 
    (null, 'justins calendar', 'justins personal calendar'),
    (null, 'groupCalendar', 'mock calendar for sales demo'),
    (null, 'bobsCalendar', 'mock calendar for sales demo');

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
        null, 'Valentina Rossi', '1988-07-01', '67.131.190.8', 50086909, '2018-05-24', FALSE);

INSERT INTO EmailCodes VALUES
    (1, '45S6Y'),
    (2, '25S6Y'),
    (3, '45T6Y');

INSERT INTO Contacts VALUES
    (23008910, 1, 'Josephine Adimari'),
    (23008911, 1, 'Andrea Iannone'),
    (23008912, 1, 'Marc Marquez');

INSERT INTO ContactAddresses VALUES
    (23008910, '1234 Sesame Street', 'Port Coquitlam', 'British Columbia', 'Canada', 'V3B1G3'),
    (23008911, '1700 Hampton Drive', 'Coquitlam', 'British Columbia', 'Canada', 'V3E3C8'),
    (23008912, '8909 Alaska Way', 'Burnaby', 'British Columbia', 'Canada', 'V5T6S9');

INSERT INTO ContactEmails VALUES
    (23008910, 'valentinorossi@gmail.com'),
    (23008911, 'aiannone0@hotmail.com'),
    (23008912, 'marquezthebiker@hotmail.com');

INSERT INTO ContactPhones VALUES
    (23008914, '6042348490', 'home'),
    (23008914, '7789083245', 'work'),
    (23008914, '6048899020', 'evening');

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

