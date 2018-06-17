/* populating tables */

INSERT INTO Calendars (calendarId, name, description) VALUES
    (1, 'mclovin calendar', 'justins personal calendar'),
    (2, 'Katies Calendar', 'mock calendar for sales demo'),
    (3, 'valentinas Calendar', 'mock calendar for sales demo'),
    (4, 'borts calendar', 'borts personal calendar'),
    (5, 'johns calendar', 'johns personal calendar'),
    (6, 'group calendar', 'mock group calendar');

INSERT INTO ItemType (type) VALUES
    ('event'),
    ('reminder'),
    ('note'),
    ('task');

INSERT INTO Accounts (id, username, email, password, salt, name, birthday, lastKnownIp, calendarId, createDate, isDeactivated) VALUES
    (1, 'mcLovin123', 'mcLovin123@gmail.com', '7c6a180b36896a0a8c02787eeafb0e4c',
        null, 'mcLovin', '1990-04-15', '208.159.243.92', 1, '2018-05-30', FALSE),
    (2, 'coolcatz', 'coolcat@aol.com', '849667e75fd3e18d98f731f87caeaa43',
        null, 'Katie Lannone', '1994-12-18', '80.254.202.72', 2, '2018-06-01', FALSE),
    (3, 'valentinarossi', 'valentinarossi@gmail.com', 'e5d910a90d8672b7cb63ef4087ab3146f45064',
        null, 'Valentina Rossi', '1988-07-01', '67.131.190.8', 3, '2018-05-24', FALSE),
    (4, 'bort', 'bort@aol.com', '123457e75fg3e18d23f731f87cacdd94',
        null, 'Bort Simpson', '1994-10-29', '80.432.202.71', 4, '2018-06-01', FALSE),
    (5, 'johndoe', 'johndoe@gmail.com', 'a5d110b90d8673a7cd63ef4087ds3146g35023',
        null, 'John Doe', '1992-03-23', '67.132.191.9', 5, '2018-05-25', FALSE);


INSERT INTO EmailCodes (accId, code) VALUES
    (1, '45S6Y'),
    (2, '25S6Y'),
    (3, '45T6Y'),
    (4, '42U2U'),
    (5, '30J3I');

INSERT INTO Contacts (contactId, accId, name, birthday) VALUES
    (1, 1, 'Josephine Adimari', '1992-12-24'),
    (2, 1, 'Andrea Iannone', '1988-12-12'),
    (3, 1, 'Marc Marquez', '1923-09-09'),
    (4, 2, 'Brock Lesnar', '1985-05-12'),
    (5, 2, 'Stephen Thompson', '1999-09-09'),
    (6, 2, 'Fedor Emilionenko', '1934-12-12'),
    (7, 2, 'Jose Aldo', '1995-03-23'),
    (8, 3, 'Conor Mcgregor', '1989-12-12'),
    (9, 3, 'Alistar Overeem', '1998-04-05'),
    (10, 3, 'Dominic Cruz', '1990-12-23'),
    (11, 4, 'Cody Garbrandt', '1999-12-12'),
    (12, 4, 'Max Holloway', '1994-12-12'),
    (13, 4, 'Brendan Schaub', '1990-10-10'),
    (14, 5, 'Bobby Lee', '1992-10-10'),
    (15, 5, 'Theo Von', '1989-10-10'),
    (16, 5, 'Chris Delia', '1990-05-23');

INSERT INTO ContactAddresses (contactId, streetField, city, state_, country, postal) VALUES
    (1, '1234 Sesame Street', 'Port Coquitlam', 'British Columbia', 'Canada', 'V3B1G3'),
    (1, '1234 Sesame ave', 'Port Coquitlam', 'British Columbia', 'Canada', 'V3B1G3'),
    (1, '1234 Sesame lane', 'Port Coquitlam', 'British Columbia', 'Canada', 'V3B1G3'),
    (2, '1700 Hampton Drive', 'Coquitlam', 'British Columbia', 'Canada', 'V3E3C8'),
    (3, '8909 Alaska Way', 'Burnaby', 'British Columbia', 'Canada', 'V5T6S9'),
    (4, '123 Fake st', 'Vancouver', 'British Columbia', 'Canada', 'V4N1N4'),
    (5, '2636 Oliver Crescent', 'Vancouver', 'British Columbia', 'Canada', 'V6J3T2'),
    (6, '111 Iona Drive', 'Surrey', 'British Columbia', 'Canada', 'V4TJ32'),
    (7, '157 Oliver Drive', 'Burnaby', 'British Columbia', 'Canada', 'V3HG2T'),
    (8, '999 Fire Lane', 'Coquitlam', 'British Columbia', 'Canada', 'V0TR9H'),
    (9, '222 Sudden Valley', 'Vancouver', 'British Columbia', 'Canada', 'V4S1N5'),
    (10, '2045 Maple st', 'Surrey', 'British Columbia', 'Canada', 'V3E1ET'),
    (11, '1580 Haro st', 'Vancouver', 'British Columbia', 'Canada', 'V9ST6T'),
    (12, '10740 168 st', 'Vancouver', 'British Columbia', 'Canada', 'V4N1N4'),
    (13, '777 Dunsmuir', 'Vancouver', 'British Columbia', 'Canada', 'V4G1G4'),
    (14, '609 Granville st', 'Vancouver', 'British Columbia', 'Canada', 'V4M1M4'),
    (15, '1412 25th ave', 'Vancouver', 'British Columbia', 'Canada', 'V4S1S4'),
    (16, '777 Howe st', 'Surrey', 'British Columbia', 'Canada', 'V4S1N4');


INSERT INTO ContactEmails (contactId, email) VALUES
    (1, 'valentinorossi@gmail.com'),
    (1, 'valentinorossi@aol.com'),
    (1, 'valentinorossi@hotmail.com'),
    (2, 'aiannone0@hotmail.com'),
    (3, 'marquezthebiker@hotmail.com'),
    (4, 'brocklesnar@gmail.com'),
    (5, 'stephenthompson@gmail.com'),
    (6, 'fedoremilionenko@gmail.com'),
    (7, 'josealdo@gmail.com'),
    (8, 'conormcgregor@gmail.com'),
    (9, 'alistarovereem@gmail.com'),
    (10, 'dominiccruz@gmail.com'),
    (11, 'codygarbrandt@gmail.com'),
    (12, 'maxholloway@gmail.com'),
    (13, 'brendanschaub@gmail.com'),
    (14, 'bobbylee@gmail.com'),
    (15, 'theovon@gmail.com'),
    (16, 'chrisdelia@gmail.com');

INSERT INTO ContactPhones (contactId, phoneNum, type) VALUES
    (1, '6042348490', 'home'),
    (1, '6042428490', 'work'),
    (1, '6042234290', 'evening'),
    (2, '7789083245', 'work'),
    (3, '6048899020', 'evening'),
    (4, '6045234234', 'home'),
    (5, '7789325292', 'work'),
    (6, '6042382932', 'evening'),
    (7, '6045329298', 'home'),
    (8, '7782522352', 'work'),
    (9, '6043924829', 'evening'),
    (10, '6045203924', 'home'),
    (11, '7782528382', 'work'),
    (12, '6041124921', 'evening'),
    (13, '6049383293', 'home'),
    (14, '7781938320', 'work'),
    (15, '6041048193', 'evening'),
    (16, '6041948193', 'home');

INSERT INTO Items (itemId, calendarId, name, createDate, note, reminder, type, createdBy, location, colour) VALUES
    (1, 6, 'event1', '2018-05-25', 'sample text 1', NULL, 'event', 1, 'Vancouver', 1),
    (2, 6, 'event2', '2018-05-21', 'sample text 2', NULL, 'event', 1, 'Vancouver', 1),
    (3, 6, 'event3', '2018-04-05', 'sample text 3', '2018-06-06 00:00:00', 'event', 2, 'Vancouver', 1),
    (4, 6, 'event4', '2018-05-24', 'sample text 4', NULL, 'event', 1, 'Vancouver', 1),
    (5, 6, 'event5', '2018-05-22', 'sample text 5', NULL, 'event', 1, 'Vancouver', 1),
    (6, 6, 'event6', '2018-04-07', 'sample text 6', '2018-06-06 00:00:00', 'event', 1, 'Vancouver', 1),
    (7, 6, 'event7', '2018-05-30', 'sample text 7', NULL, 'event', 1, 'Vancouver', 1),
    (8, 6, 'event8', '2018-05-05', 'sample text 8', NULL, 'event', 1, 'Vancouver', 1),
    (9, 6, 'event9', '2018-04-25', 'sample text 9', '2018-06-06 00:00:00', 'event', 1, 'Vancouver', 1),
    (10, 6, 'event10', '2018-03-23', 'sample text 10', NULL, 'event', 3, 'Vancouver', 1),
    (11, 6, 'task1', '2018-05-22', 'sample text 1', NULL, 'task', 1, 'Vancouver', 2),
    (12, 6, 'task2', '2018-05-21', 'sample text 2', NULL, 'task', 1, 'Vancouver', 2),
    (13, 6, 'task3', '2018-04-05', 'sample text 3', '2018-04-06 00:00:00', 'task', 3, 'Vancouver', 2),
    (14, 6, 'task4', '2018-05-25', 'sample text 4', NULL, 'task', 1, 'Vancouver', 2),
    (15, 6, 'task5', '2018-05-20', 'sample text 5', NULL, 'task', 1, 'Vancouver', 2),
    (16, 6, 'task6', '2018-04-03', 'sample text 6', '2018-04-06 00:00:00', 'task', 3, 'Vancouver', 2),
    (17, 6, 'task7', '2018-05-19', 'sample text 7', NULL, 'task', 1, 'Vancouver', 2),
    (18, 6, 'task8', '2018-05-14', 'sample text 8', NULL, 'task', 1, 'Vancouver', 2),
    (19, 6, 'task9', '2018-04-12', 'sample text 9', '2018-04-06 00:00:00', 'task', 4, 'Vancouver', 2),
    (20, 6, 'task10', '2018-05-05', 'sample text 10', NULL, 'task', 1, 'Vancouver', 2),
    (21, 6, 'note1', '2018-05-25', 'sample text 1', NULL, 'note', 1, 'Vancouver', 4),
    (22, 6, 'note2', '2018-05-21', 'sample text 2', NULL, 'note', 1, 'Vancouver', 4),
    (23, 6, 'note3', '2018-04-05', 'sample text 3', '2018-06-06 00:00:00', 'note', 1, 'Vancouver', 4),
    (24, 6, 'note4', '2018-05-24', 'sample text 4', NULL, 'note', 5, 'Vancouver', 4),
    (25, 6, 'note5', '2018-05-22', 'sample text 5', NULL, 'note', 1, 'Vancouver', 4),
    (26, 6, 'note6', '2018-04-07', 'sample text 6', '2018-06-06 00:00:00', 'note', 1, 'Vancouver', 4),
    (27, 6, 'note7', '2018-05-30', 'sample text 7', NULL, 'note', 1, 'Vancouver', 4),
    (28, 6, 'note8', '2018-05-05', 'sample text 8', NULL, 'note', 5, 'Vancouver', 4),
    (29, 6, 'note9', '2018-04-25', 'sample text 9', '2018-06-06 00:00:00', 'note', 1, 'Vancouver', 4),
    (30, 6, 'note10', '2018-03-23', 'sample text 10', NULL, 'note', 1, 'Vancouver', 4),
    (31, 6, 'reminder1', '2018-05-22', 'sample text 1', NULL, 'reminder', 1, 'Vancouver', 3),
    (32, 6, 'reminder2', '2018-05-21', 'sample text 2', NULL, 'reminder', 2, 'Vancouver', 3),
    (33, 6, 'reminder3', '2018-04-05', 'sample text 3', '2018-04-06 00:00:00', 'reminder', 1, 'Vancouver', 3),
    (34, 6, 'reminder4', '2018-05-25', 'sample text 4', NULL, 'reminder', 1, 'Vancouver', 3),
    (35, 6, 'reminder5', '2018-05-20', 'sample text 5', NULL, 'reminder', 1, 'Vancouver', 3),
    (36, 6, 'reminder6', '2018-04-03', 'sample text 6', '2018-04-06 00:00:00', 'reminder', 1, 'Vancouver', 3),
    (37, 6, 'reminder7', '2018-05-19', 'sample text 7', NULL, 'reminder', 2, 'Vancouver', 3),
    (38, 6, 'reminder8', '2018-05-14', 'sample text 8', NULL, 'reminder', 3, 'Vancouver', 3),
    (39, 6, 'reminder9', '2018-04-12', 'sample text 9', '2018-04-06 00:00:00', 'reminder', 1, 'Vancouver', 3),
    (40, 6, 'reminder10', '2018-05-05', 'sample text 10', NULL, 'reminder', 1, 'Vancouver', 3);

INSERT INTO EventItems (itemId, startDate, endDate) VALUES
    (1, '2018-05-25', '2018-05-30'),
    (2, '2018-05-21', '2018-05-30'),
    (3, '2018-04-05', '2018-05-30'),
    (4, '2018-05-24', '2018-05-30'),
    (5, '2018-05-22', '2018-05-30'),
    (6, '2018-04-07', '2018-05-30'),
    (7, '2018-05-30', '2018-05-30'),
    (8, '2018-05-05', '2018-05-30'),
    (9, '2018-04-25', '2018-05-30'),
    (10, '2018-03-23', '2018-05-30');

INSERT INTO TaskItems (itemId, dueDate, completionDate) VALUES
    (11, '2018-05-25', '2018-05-30'),
    (12, '2018-05-21', '2018-05-30'),
    (13, NULL, '2018-05-30'),
    (14, '2018-05-29', '2018-05-30'),
    (15, '2018-05-28', '2018-05-30'),
    (16, NULL, '2018-05-30'),
    (17, '2018-05-27', '2018-05-30'),
    (18, '2018-05-26', '2018-05-30'),
    (19, NULL, '2018-05-30'),
    (20, '2018-05-26', '2018-05-30');

INSERT INTO Groups (accId, calendarId, permissionType) VALUES
    (1, 1, 'user'),
    (2, 2, 'user'),
    (3, 3, 'user'),
    (4, 4, 'user'),
    (5, 5, 'user'),
    (1, 6, 'admin'),
    (2, 6, 'viewer'),
    (3, 6, 'viewer'),
    (4, 6, 'viewer'),
    (5, 6, 'viewer');
