CREATE DATABASE NURSING

CREATE TABLE USER(
	CWID int NOT NULL, 
	LastName varchar(255) NOT NULL,
	FirstName varchar(255) NOT NULL,
	Role enum('1', '5', '9') NOT NULL,
	Email varchar(255) NOT NULL,
	PRIMARY KEY (CWID)
);

CREATE TABLE ROOM(
	Capacity int NOT NULL,
	Room_Number varchar(255) NOT NULL,
	Type varchar(255) NOT NULL,
	Description varchar(255),
	PRIMARY KEY (Room_Number)
);

CREATE TABLE COURSE(
	Prefix varchar(255) NOT NULL,
	Course_Number int NOT NULL,
	Title varchar(255) NOT NULL,
	PRIMARY KEY (Course_Number)
);

CREATE TABLE SECTION(
	Course_Number int FOREIGN KEY REFERENCES COURSE(Course_Number),
	CRN int NOT NULL
	PRIMARY KEY (CRN)
);

CREATE TABLE RESERVE(
	CRN int FOREIGN KEY REFERENCES SECTION(CRN),
	Time time
	CWID int FOREIGN KEY REFERENCES USER(CWID),
	Room_Number FOREIGN KEY REFERENCES ROOM(ROOM_NUMBER),
	StartDay date
	EndDay date
	
);

CREATE TABLE CONFLICT(
	ID int NOT NULL AUTO_INCREMENT,
	CRN int FOREIGN KEY REFERENCES SECTION(CRN),
	Time time
	CWID int FOREIGN KEY REFERENCES USER(CWID),
	Room_Number FOREIGN KEY REFERENCES ROOM(ROOM_NUMBER),
	Day date
	PRIMARY KEY (ID)
);

--2018-02-24
CREATE TABLE DATE(
	ID int NOT NULL AUTO_INCREMENT,
	DATE_OPEN datetime NOT NULL,
	DATE_CLOSE datetime NOT NULL,
	CURRENT int(1) NOT NULL --0 for not current, 1 for current
);
