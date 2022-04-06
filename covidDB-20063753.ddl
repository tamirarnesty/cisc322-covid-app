/* Tamir Arnesty - 20063753
    Feb. 9, 2022
    COVID Database Relational Model Implementation
*/
DROP DATABASE IF EXISTS covidDB;
CREATE DATABASE covidDB;

/* Entities:
    - Company
    - Vaccine
    - VaccinationSite
    - Patient
    - Spouse
    - Doctor
    - Nurse
    - MedicalPractice
*/

CREATE TABLE Company(
    Name                VARCHAR(30)     NOT NULL,
    Street              VARCHAR(30)     NOT NULL,
    City                VARCHAR(30)     NOT NULL,
    PostalCode          CHAR(6)         NOT NULL,
    Province            CHAR(2)         NOT NULL,
    Country             CHAR(2)         NOT NULL,
    PRIMARY KEY(Name));

CREATE TABLE Vaccine(
    LotNumber               CHAR(6)     NOT NULL,
    DoseCount               INTEGER     NOT NULL,
    ProductionDate          DATE        NOT NULL,
    ExpirationDate          DATE        NOT NULL,
    Company                 VARCHAR(30) NOT NULL,
    PRIMARY KEY(LotNumber),
    FOREIGN KEY(Company) REFERENCES Company(Name) ON DELETE CASCADE); # if company is deleted, all associated vaccines are deleted

CREATE TABLE VaccinationSite(
    Name                VARCHAR(30)     NOT NULL,
    Street              VARCHAR(30)     NOT NULL,
    City                VARCHAR(30)     NOT NULL,
    PostalCode          CHAR(6)         NOT NULL,
    Province            CHAR(2)         NOT NULL,
    Country             CHAR(2)         NOT NULL,
    PRIMARY KEY(Name));

CREATE TABLE OperationDates(
    SiteName        VARCHAR(30)     NOT NULL,
    OperationDate   DATE            NOT NULL,
    PRIMARY KEY(SiteName, OperationDate),
    FOREIGN KEY(SiteName) REFERENCES VaccinationSite(Name) ON DELETE CASCADE); # if site is deleted, all associated operation dates are deleted

/* Patient and Spouse */
CREATE TABLE Patient(
    OHIPNumber      CHAR(12)        NOT NULL,
    Birthdate       DATE,
    FirstName       VARCHAR(30),
    MiddleName      VARCHAR(30),
    LastName        VARCHAR(30),
    PRIMARY KEY(OHIPNumber));

CREATE TABLE Spouse(
    OHIPNumber      CHAR(12)        NOT NULL,
    PhoneNumber     CHAR(11)        NOT NULL,
    FirstName       VARCHAR(30),
    MiddleName      VARCHAR(30),
    LastName        VARCHAR(30),
    /* Patient as HAS relationship to Spouse */
    PatientOHIP     CHAR(12)        NOT NULL,
    PRIMARY KEY(OHIPNumber),
    FOREIGN KEY(PatientOHIP) REFERENCES Patient(OHIPNumber));

CREATE TABLE Nurse(
    ID              CHAR(6)         NOT NULL,
    FirstName       VARCHAR(30),
    MiddleName      VARCHAR(30),
    LastName        VARCHAR(30),
    PRIMARY KEY(ID));

CREATE TABLE NurseCredentials(
    ID              CHAR(6)         NOT NULL,
    Credential      VARCHAR(30)     NOT NULL,
    PRIMARY KEY(ID, Credential),
    FOREIGN KEY(ID) REFERENCES Nurse(ID) ON DELETE CASCADE); # if nurse is deleted, all associated credentials are deleted

/* Doctor and MedicalPractice */
CREATE TABLE MedicalPractice(
    Name            VARCHAR(30)     NOT NULL,
    PhoneNumber     CHAR(11)        NOT NULL,
    PRIMARY KEY(Name));

CREATE TABLE Doctor(
    ID              CHAR(6)         NOT NULL,
    FirstName       VARCHAR(30),
    MiddleName      VARCHAR(30),
    LastName        VARCHAR(30),
    MedicalPractice VARCHAR(30)     NOT NULL,
    PRIMARY KEY(ID),
    FOREIGN KEY(MedicalPractice) REFERENCES MedicalPractice(Name) ON DELETE CASCADE); # if medical practice is deleted, all associated doctors are deleted

CREATE TABLE DoctorCredentials(
    ID              CHAR(6)         NOT NULL,
    Credential      VARCHAR(30)     NOT NULL,
    PRIMARY KEY(ID, Credential),
    FOREIGN KEY(ID) REFERENCES Doctor(ID) ON DELETE CASCADE); # if doctor is deleted, all associated credentials are deleted

/* Relationships
    - Vaccination (Vaccine, Patient): (M, N)
    - ShipsTo (Vaccine, VaccinationSite): (M, N)
    - DoctorWorksAt (Doctor, VaccinationSite): (M, N)
    - NurseWorksAt (Nurse, VaccinationSite): (M, N)

    Included in entity:
    - Produces (Company, Vaccine): (N, 1)
    - AssociatedTo (Doctor, MedicalPractice): (M, 1)
*/

/* Patient and Vaccine Relationship */
CREATE TABLE Vaccination(
    OHIPNumber      CHAR(12)    NOT NULL,
    LotNumber       CHAR(6)     NOT NULL,
    VaccinationSite VARCHAR(30),
    VaccinationDate DATE        NOT NULL,
    VaccinationTime TIME,
    PRIMARY KEY(OHIPNumber, LotNumber),
    FOREIGN KEY(OHIPNumber) REFERENCES Patient(OHIPNumber),
    FOREIGN KEY(LotNumber) REFERENCES Vaccine(LotNumber) ON DELETE CASCADE,
    FOREIGN KEY(VaccinationSite) REFERENCES VaccinationSite(Name) ON DELETE SET NULL); # if patient or vaccine lot is deleted, all associated vaccinations are deleted

/* Doctor works at VaccinationSite */
CREATE TABLE DoctorWorksAt(
    DoctorID    CHAR(6)         NOT NULL,
    SiteName    VARCHAR(30)     NOT NULL,
    PRIMARY KEY(DoctorID, SiteName),
    FOREIGN KEY(DoctorID) REFERENCES Doctor(ID) ON DELETE CASCADE,
    FOREIGN KEY(SiteName) REFERENCES VaccinationSite(Name) ON DELETE CASCADE); # if doctor or site is deleted, all associated DoctorWorksAt relationships are deleted

/* Nurse works at VaccinationSite */
CREATE TABLE NurseWorksAt(
    NurseID     CHAR(6)         NOT NULL,
    SiteName    VARCHAR(30)     NOT NULL,
    PRIMARY KEY(NurseID, SiteName),
    FOREIGN KEY(NurseID) REFERENCES Nurse(ID) ON DELETE CASCADE,
    FOREIGN KEY(SiteName) REFERENCES VaccinationSite(Name) ON DELETE CASCADE); # if nurse or site is deleted, all associated NurseWorksAt relationships are deleted

/* Vaccine ships to VaccinationSite */
CREATE TABLE ShipsTo(
    LotNumber       CHAR(6)         NOT NULL,
    SiteName        VARCHAR(30)     NOT NULL,
    PRIMARY KEY(LotNumber, SiteName),
    FOREIGN KEY(LotNumber) REFERENCES Vaccine(LotNumber) ON DELETE CASCADE,
    FOREIGN KEY(SiteName) REFERENCES VaccinationSite(Name) ON DELETE CASCADE); # if vaccine or site is deleted, all associated ShipsTo relationships are deleted

/* Sample Data */
INSERT INTO Company VALUES
('Moderna', '123 Main St.', 'Toronto', 'M4E2V9', 'ON', 'CA'),
('Pfizer', '172 Main St.', 'Toronto', 'M4E2W1', 'ON', 'CA'),
('AstraZeneca', '510 Main St.', 'East York', 'M4C4Y2', 'ON', 'CA'),
('Johnson & Johnson', '213 Main St.', 'Toronto', 'M4E2H2', 'ON', 'CA');

INSERT INTO Vaccine VALUES
/* Lot, Dose, ProductionDate, ExpirDate, Company */
/* Lot number is 6 characters of 2 letters and 4 digits */
/* ExpirationDate 6 months after ProductionDate */
('AY3456', 500, '2020-04-01', '2020-10-01', 'Pfizer'),
('XF2345', 150, '2020-05-01', '2020-11-01', 'Moderna'),
('BC4567', 750, '2020-04-01', '2020-10-01', 'AstraZeneca'),
('BF5678', 300, '2020-03-01', '2020-09-01', 'Johnson & Johnson'),
('JH6789', 275, '2020-06-01', '2020-12-01', 'Pfizer'),
('PL7890', 800, '2020-08-01', '2021-02-01', 'Moderna'),
('VE8901', 250, '2020-07-01', '2021-01-01', 'AstraZeneca'),
('GU9012', 925, '2020-09-01', '2021-03-01', 'Johnson & Johnson');

INSERT INTO VaccinationSite VALUES
/* Name, Street, City, PostalCode, Province, Country */
('KGH', '123 Main St.', 'Kingston', 'M5J2N2', 'ON', 'CA'),
('Ajax Health Clinic', '234 Main St.', 'Ajax', 'L1S4K9', 'ON', 'CA'),
('Queen\'s University', '345 Main St.', 'Kingston', 'H3B2N2', 'ON', 'CA'),
('St Lawrence College', '456 Main St.', 'Kingston', 'H3B2N2', 'ON', 'CA'),
('Toronto Western Hospital', '789 Main St.', 'Toronto', 'V5J2N2', 'ON', 'CA');

INSERT INTO ShipsTo VALUES
/* LotNumber, SiteName */
('AY3456', 'KGH'),
('BC4567', 'KGH'),
('GU9012', 'KGH'),
('JH6789', 'KGH'),

('XF2345', 'Ajax Health Clinic'),
('VE8901', 'Ajax Health Clinic'),
('GU9012', 'Ajax Health Clinic'),

('BC4567', 'Queen\'s University'),
('JH6789', 'Queen\'s University'),

('BF5678', 'St Lawrence College'),
('AY3456', 'St Lawrence College'),
('VE8901', 'St Lawrence College'),

('JH6789', 'Toronto Western Hospital'),
('BF5678', 'Toronto Western Hospital'),
('PL7890', 'Toronto Western Hospital');

INSERT INTO MedicalPractice VALUES
/* Name, PhoneNumber */
('Toronto Health', '4161234567'),
('KGH', '6131234567'),
('Ajax Center', '9051234567'),
('St Lawrence College', '5451234567'),
('Ottawa Clinic', '4541234567'),
('Quebec Better', '4331234567');

INSERT INTO OperationDates VALUES
/* VaccionationSiteName, OperationDate */
('KGH', '2020-08-01'),
('KGH', '2020-08-02'),
('KGH', '2020-08-03'),

('Ajax Health Clinic', '2020-05-01'),
('Ajax Health Clinic', '2020-06-01'),
('Ajax Health Clinic', '2020-07-01'),

('St Lawrence College', '2021-01-25'),
('St Lawrence College', '2021-01-26'),

('Toronto Western Hospital', '2021-02-01'),
('Toronto Western Hospital', '2021-02-02');

INSERT INTO Patient VALUES
/* OHIPNumber, DOB, FirstName, MiddleName (Optional), LastName */
('1234567890AB', '1955-01-01', 'Alexander', 'James', 'Hamilton'),
('1234567890EF', '1957-01-01', 'Steve', '', 'Jobs'),
('1234567890GH', '1958-01-01', 'Paul', '', 'Walker'),
('1234567890MN', '1970-01-01', 'Ben', 'Pete', 'Parker'),
('1234567890OP', '1975-01-01', 'David', 'Lawrence', 'McCary'),
('1234567890QR', '1985-01-01', 'Tobey', '', 'Maguire'),
('1234567890ST', '1995-01-01', 'Tom', '', 'Cruise'),
('1234567890UV', '2005-01-01', 'Tom', 'HoHo', 'Holland');

INSERT INTO Spouse VALUES
/* OHIPNumber, PhoneNumber, FirstName, MiddleName (Optional), LastName, PatientOHIPNumber */
('0987654321BA', '4161234567', 'Elizabeth', '', 'Schuyler', '1234567890AB'),
('0987654321IJ', '4161234567', 'May', '', 'Parker', '1234567890MN'),
('0987654321KL', '4161234567', 'Emma', 'Jean', 'Stone', '1234567890OP'),
('0987654321MN', '4161234567', 'Mary', 'Jane', 'Maguire', '1234567890QR'),
('0987654321QR', '4161234567', 'Mary', 'Jane', 'Watson', '1234567890UV');

INSERT INTO Vaccination VALUES
/* OHIPNumber, LotNumber, VaccinationSite, VaccinationDate, VaccinationTime */
/* KGH */
('1234567890AB', 'AY3456', 'KGH', '2020-08-01', '09:00:00'),
('1234567890MN', 'BC4567', 'KGH', '2020-08-02', '09:30:00'),
('1234567890EF', 'GU9012', 'KGH', '2020-08-03', '09:45:00'),

/* St Lawrence Hospital */
('1234567890GH', 'BF5678', 'St Lawrence College', '2021-01-25', '09:45:00'),
('1234567890OP', 'VE8901', 'St Lawrence College', '2021-01-26', '10:15:00'),

/* Toronto Western Hospital */
('1234567890QR', 'JH6789', 'Toronto Western Hospital', '2021-02-01', '12:30:00'),
('1234567890ST', 'PL7890', 'Toronto Western Hospital', '2021-02-02', '12:30:00'),
('1234567890EF', 'BF5678', 'Toronto Western Hospital', '2021-02-02', '12:45:00');

INSERT INTO Nurse VALUES
/* ID, FirstName, MiddleName (Optional), LastName */
('N01001', 'Nancy', '', 'Nursely'),
('N20220', 'Stanley', 'Do', 'Goody'),
('N30003', 'Frank', '', 'Harley'),
('N44044', 'George', 'Neil', 'O\'Malley'),
('N00505', 'Barbara', 'Darbara', 'Streisand');

INSERT INTO NurseCredentials VALUES
/* ID, Credential */
('N01001', 'RN'),
('N01001', 'CWCN'),
('N20220', 'NP'),
('N30003', 'LPN'),
('N30003', 'CARN'),
('N44044', 'APRN'),
('N00505', 'CPN'),
('N00505', 'OCN');

INSERT INTO Doctor VALUES
/* ID, FirstName, MiddleName (Optional), LastName, MedicalPractice */
('D01101', 'Otto', 'Gunther', 'Octavius', 'Toronto Health'),
('D02202', 'Norman', '', 'Osborn', 'KGH'),
('D03303', 'Victor', 'H', 'Frankenstein', 'Ajax Center'),
('D04404', 'Farrow', '', 'Tarrow', 'St Lawrence College'),
('D05505', 'Lay', '', 'Low', 'Ottawa Clinic');

INSERT INTO DoctorCredentials VALUES
/* ID, Credential */
('D01101', 'MD'),
('D01101', 'DO'),
('D02202', 'MD CM'),
('D03303', 'MB BCh BAO'),
('D03303', 'DC'),
('D04404', 'DPM'),
('D04404', 'MB BS'),
('D05505', 'PharmD');

INSERT INTO DoctorWorksAt VALUES
/* DoctorID, VaccinationSite */
('D01101', 'KGH'),
('D02202', 'KGH'),
('D03303', 'Ajax Health Clinic'),
('D04404', 'St Lawrence College'),
('D05505', 'Queen\'s University');

INSERT INTO NurseWorksAt VALUES
/* NurseID, VaccinationSite */
('N01001', 'KGH'),
('N01001', 'St Lawrence College'),
('N20220', 'Queen\'s University'),
('N20220', 'KGH'),
('N30003', 'Ajax Health Clinic'),
('N44044', 'St Lawrence College'),
('N44044', 'KGH'),
('N00505', 'Queen\'s University');
