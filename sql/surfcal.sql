CREATE SCHEMA `surfcal`;

CREATE TABLE Calendars (
    calendarId  INTEGER AUTO_INCREMENT,
    name        VARCHAR(32) NOT NULL,
    description VARCHAR(256) NOT NULL,
    PRIMARY KEY (calendarId)
);

CREATE TABLE ItemType (
    type         ENUM('event', 'task', 'reminder', 'note') NOT NULL,
    PRIMARY KEY (type)
);

CREATE TABLE Accounts (
    id          INTEGER AUTO_INCREMENT,
    username    VARCHAR(32) NOT NULL,
    email       VARCHAR(64) NOT NULL,
    password    VARCHAR(64),
    salt        VARCHAR(64),
    name        VARCHAR(32) NOT NULL,
    birthday    DATE NOT NULL,
    lastKnownIp VARCHAR(32),
    calendarId  INTEGER NOT NULL,
    createDate  DATE NOT NULL,
    isDeactivated BOOLEAN,
    PRIMARY KEY (id),
    UNIQUE (username),
    UNIQUE (email),
    UNIQUE (calendarId),
    FOREIGN KEY (calendarId) REFERENCES Calendars(calendarId)
);

CREATE TABLE EmailCodes (
    accId       INTEGER,
    code        VARCHAR(32) NOT NULL,
    PRIMARY KEY (accId),
    FOREIGN KEY (accId) REFERENCES Accounts(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Contacts (
    contactId   INTEGER AUTO_INCREMENT,
    accId       INTEGER,
    name        VARCHAR(32) NOT NULL,
    birthday    DATE,
    PRIMARY KEY (contactId),
    FOREIGN KEY (accId) REFERENCES Accounts(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE ContactAddresses (
    contactId   INTEGER,
    streetField VARCHAR(256) NOT NULL,
    city        VARCHAR(32) NOT NULL,
    state_      VARCHAR(32) NOT NULL,
    country     VARCHAR(32) NOT NULL,
    postal      CHAR(8) NOT NULL,
    PRIMARY KEY (contactId, streetField, city, state_, country, postal),
    FOREIGN KEY (contactId) REFERENCES Contacts(contactId) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE ContactEmails (
    contactId   INTEGER,
    email       VARCHAR(64) NOT NULL,
    PRIMARY KEY (contactId, email),
    FOREIGN KEY (contactId) REFERENCES Contacts(contactId) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE ContactPhones (
    contactId   INTEGER,
    phoneNum    VARCHAR(32) NOT NULL,
    type        ENUM('home', 'work', 'evening', 'cell', 'iPhone', 'other') NOT NULL,
    PRIMARY KEY (contactId, phoneNum),
    FOREIGN KEY (contactId) REFERENCES Contacts(contactId) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Items (
    itemId      INTEGER AUTO_INCREMENT,
    calendarId  INTEGER,
    name        VARCHAR(32),
    createDate  DATE NOT NULL,
    note        VARCHAR(256),
    reminder    DATETIME,
    type        ENUM('event', 'task', 'reminder', 'note') NOT NULL,
    createdBy   INTEGER,
    location    VARCHAR(32),
    colour      INTEGER,
    PRIMARY KEY (itemId),
    FOREIGN KEY (calendarId) REFERENCES Calendars(calendarId) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (type) REFERENCES ItemType(type) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE EventItems (
    itemId      INTEGER,
    startDate   DATETIME NOT NULL,
    endDate     DATETIME,
    PRIMARY KEY (itemId),
    FOREIGN KEY (itemId) REFERENCES Items(itemId) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE TaskItems (
    itemId      INTEGER,
    dueDate     DATETIME NOT NULL,
    completionDate DATETIME,
    PRIMARY KEY (itemId),
    FOREIGN KEY (itemId) REFERENCES Items(itemId) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Groups (
    accId       INTEGER,
    calendarId  INTEGER,
    permissionType ENUM('viewer', 'user', 'admin'),
    PRIMARY KEY (accId, calendarId),
    FOREIGN KEY (accId) REFERENCES Accounts(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (calendarId) REFERENCES Calendars(calendarId) ON DELETE CASCADE ON UPDATE CASCADE
);



