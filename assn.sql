/* 1. Record a vaccination for a patient.  You should first ask the person for their OHIP number.  If the patient doesn't exist in the database, you will need to prompt for the patient information (don't worry about spouse) and add the patient first before asking for vaccination data. 
    - Once the patient is in the database, ask for the vaccination data: which clinic the vaccine was administered at (list them and let the user choose), the lot number of the vaccine that they were given.
    - Record the vaccination.
    - List all vaccinations for this patient after you update the vaccination table.
*/
insert into Patient value ('1234567890KJ', '1955-01-01', 'Alexander', '', 'Hamilton');

/* 2. Allow the user to choose a vaccine type and display all the vaccination sites that have (or will) offer that type of vaccine along with the total number of doses that have shipped to that site. */
SELECT SiteName, sum(DoseCount) as VaccineDoses FROM Vaccine as v join ShipsTo as s on v.LotNumber=s.LotNumber WHERE v.Company='Pfizer' group by s.SiteName order by s.SiteName;

/* 3. Allow the user to choose a patient (from the list of patients in the database) and show their vaccination status -- ie. the patient's name, ohip number, the date the vaccine was given and the type of vaccine that was given. */
SELECT FirstName, MiddleName, LastName FROM Patient;
SELECT * FROM Vaccination as v join Patient as p on p.OHIPNumber=v.OHIPNumber where p.OHIPNumber='1234567890AB';

/* 4. Show a listing of all workers that work at a vaccination site (chosen by the user).  Show their name and whether they are a doctor or a nurse. */
SELECT n.FirstName, n.MiddleName, n.LastName FROM Nurse as n, NurseWorksAt as nw WHERE nw.SiteName='KGH' and nw.NurseID=n.ID;
SELECT d.FirstName, d.MiddleName, d.LastName FROM Doctor as d, DoctorWorksAt as dw WHERE dw.SiteName='KGH' and dw.DoctorID=d.ID;