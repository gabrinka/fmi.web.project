-- CREATE DATABASE schedule;

-- USE schedule;


-- Create Tables
CREATE TABLE buildings (
 building VARCHAR(255) NOT NULL,
 floors VARCHAR(255) NOT NULL
);

CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
	  userType CHAR(1) NOT NULL,
	  fn VARCHAR(10),
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE floorMap (
 building VARCHAR(255) NOT NULL,
 floor INTEGER NOT NULL,
 map VARCHAR(1024)
);

CREATE TABLE rooms (
 building VARCHAR(255) NOT NULL,
 room VARCHAR(25) NOT NULL,
 floor INTEGER NOT NULL,
 type INTEGER,
 seatsCnt INTEGER,
 computers CHAR(1),
 whiteBoard CHAR(1),
 projector CHAR(1),
 sector CHAR(1)
);

CREATE TABLE subjects (
 title CHAR(255) NOT NULL,
 type CHAR(1) NOT NULL,
 lecturer CHAR(255) NOT NULL,
 duration INTEGER,
 computers CHAR(1),
 whiteBoard CHAR(1),
 projector CHAR(1)
);

CREATE TABLE studentsGroups (
 speciality VARCHAR(255) NOT NULL,
 potok INTEGER,
 groupAdm INTEGER NOT NULL,
 year INTEGER NOT NULL,
 count INTEGER
);


CREATE TABLE roomTaken  (
   building VARCHAR(255) NOT NULL,
   room VARCHAR(25) NOT NULL,
   floor INTEGER NOT NULL,
   title CHAR(255) NOT NULL,
   type CHAR(1) NOT NULL,
   lecturer CHAR(255) NOT NULL,
   speciality VARCHAR(255) NOT NULL,
   groupAdm INTEGER NOT NULL,
   year INTEGER NOT NULL,
   date DATETIME NOT NULL,
   duration INTEGER NOT NULL
  );

-- Create Constraints
ALTER TABLE buildings ADD CONSTRAINT PK_buildings PRIMARY KEY(building);

ALTER TABLE floorMap ADD CONSTRAINT PK_floorMap PRIMARY KEY(building,floor);

ALTER TABLE rooms ADD CONSTRAINT PK_rooms PRIMARY KEY(building,room,floor);

ALTER TABLE subjects ADD CONSTRAINT PK_subjects PRIMARY KEY(title,type,lecturer);

ALTER TABLE studentsGroups ADD CONSTRAINT PK_studentsGroups PRIMARY KEY(speciality,groupAdm,year);

ALTER TABLE roomTaken ADD CONSTRAINT PK_roomTaken PRIMARY KEY(building,room,floor,speciality,groupAdm,date,duration);

ALTER TABLE roomTaken ADD CONSTRAINT FK_roomTaken_rooms FOREIGN KEY(building,room,floor) REFERENCES rooms(building,room,floor);

ALTER TABLE roomTaken ADD CONSTRAINT FK_roomTaken_subjects FOREIGN KEY(title,type,lecturer) REFERENCES subjects(title,type,lecturer);

ALTER TABLE roomTaken ADD CONSTRAINT FK_roomTaken_studentsGroups FOREIGN KEY(speciality,groupAdm,year) REFERENCES studentsGroups(speciality,groupAdm, year);


-- Insert Data
INSERT INTO buildings
  VALUES 
  ('ФМИ', '0,1,2,3,4,5'),
  ('ФХФ', '1,2,6'),
  ('ФзФ', '2');

INSERT INTO floorMap
  VALUES
  ('ФМИ', 0, '01 02 t x x 013 014 t|03 04 s x x x x s 018 019 020'),
  ('ФМИ', 1, 'x x x x x 113 114 x x 120 x|t 101 s 111 x x x s x 122 t'),
  ('ФМИ', 2, 'x x x 210 x x x x 217 x 222|t x x s x 200 s 229'),
  ('ФМИ', 3, '302 303 304 305 306 307 308 309 310 311 312 313 314 x|t 326 s x 325 323 s 321 320 t'),
  ('ФМИ', 4, 'x x x 401 402 x 404 405 x x x x|x x x s x x x x s x x x'),
  ('ФМИ', 5, 'x 504 x x 512 513 514 x 501 500 x|t x 526 s x x x x s x x t'),
  ('ФХФ', 1, '130|s'),
  ('ФХФ', 2, '210|s t'),
  ('ФХФ', 6, '603 604 s x x x x x x x|x x x x x x s 601'),
  ('ФзФ', 2, 'x x A209 A207 x|t s x x x x s x x');




INSERT INTO rooms
  VALUES
  ('ФХФ', '210', 2, 3, 260, 'н', 'н', 'д', 'ц'),
  ('ФХФ', '130', 1, 3, 260, 'н', 'н', 'д', 'ц'),
  ('ФХФ', '603', 6, 1, 40, 'н', 'д', 'н', 'д'),
  ('ФХФ', '604', 6, 1, 48, 'н', 'д', 'н', 'д'),
  ('ФХФ', '601', 6, 3, 78, 'н', 'н', 'н', 'л'),
  
  ('ФзФ', 'A207', 2, 3, 140, 'н', 'н', 'н', 'д'),
  ('ФзФ', 'A209', 2, 3, 140, 'н', 'н', 'н', 'д'),
  
  ('ФМИ', '01', 0, 3, 80, 'н', 'д', 'д', 'л'),
  ('ФМИ', '02', 0, 3, 60, 'н', 'д', 'д', 'л'),
  ('ФМИ', '03', 0, 1, 18, 'н', 'д', 'н', 'л'),
  ('ФМИ', '04', 0, 2, 24, 'н', 'д', 'н', 'л'),
  ('ФМИ', '013', 0, 1, 14, 'д', 'д', 'д', 'д'),
  ('ФМИ', '014', 0, 1, 14, 'д', 'д', 'д', 'д'),
  ('ФМИ', '018', 0, 1, 12, 'д', 'д', 'д', 'д'),
  ('ФМИ', '019', 0, 1, 15, 'д', 'д', 'д', 'д'),
  ('ФМИ', '020', 0, 1, 15, 'д', 'д', 'д', 'д'),

  ('ФМИ', '101', 1, 3, 112, 'н', 'н', 'д', 'л'),
  ('ФМИ', '107', 1, 2, 24, 'д', 'д', 'д', 'л'),
  ('ФМИ', '120', 1, 2, 24, 'д', 'д', 'д', 'д'),
  ('ФМИ', '122', 1, 1, 11, 'д', 'д', 'д', 'ц'),
  ('ФМИ', '113', 1, 1, 12, 'н', 'д', 'д', 'д'),
  ('ФМИ', '114', 1, 2, 18, 'н', 'д', 'д', 'д'),
  ('ФМИ', '111', 1, 1, 10, 'н', 'н', 'н', 'ц'),
  
  ('ФМИ', '200', 2, 3, 154, 'н', 'н', 'д', 'ц'),
  ('ФМИ', '229', 2, 3, 80, 'н', 'д', 'д', 'д'),
  ('ФМИ', '210', 2, 1, 26, 'н', 'д', 'н', 'ц'),
  ('ФМИ', '217', 2, 1, 20, 'н', 'д', 'н', 'д'),
  ('ФМИ', '222', 2, 2, 19, 'д', 'д', 'д', 'д'),
  
  ('ФМИ', '325', 3, 3, 170, 'н', 'д', 'д', 'ц'),
  ('ФМИ', '326', 3, 3, 68, 'н', 'д', 'д', 'л'),
  ('ФМИ', '306', 3, 1, 20, 'д', 'д', 'д', 'л'),
  ('ФМИ', '309', 3, 1, 18, 'д', 'д', 'д', 'ц'),
  ('ФМИ', '313', 3, 1, 7, 'д', 'д', 'д', 'д'),
  ('ФМИ', '314', 3, 2, 18, 'д', 'д', 'д', 'д'),
  ('ФМИ', '320', 3, 2, 20, 'д', 'д', 'д', 'д'),
  ('ФМИ', '321', 3, 1, 18, 'д', 'д', 'д', 'д'),
  ('ФМИ', '323', 3, 1, 15, 'д', 'д', 'д', 'ц'),
  ('ФМИ', '302', 3, 1, 18, 'н', 'н', 'н', 'л'),
  ('ФМИ', '303', 3, 1, 18, 'н', 'д', 'н', 'л'),
  ('ФМИ', '304', 3, 1, 18, 'н', 'д', 'н', 'л'),
  ('ФМИ', '305', 3, 1, 18, 'н', 'д', 'н', 'л'),
  ('ФМИ', '307', 3, 1, 30, 'н', 'н', 'н', 'ц'),
  ('ФМИ', '308', 3, 1, 30, 'н', 'д', 'н', 'ц'),
  ('ФМИ', '310', 3, 1, 30, 'н', 'д', 'н', 'ц'),
  ('ФМИ', '311', 3, 1, 30, 'н', 'д', 'н', 'ц'),
  
	
  ('ФМИ', '401', 4, 1, 30, 'н', 'д', 'н', 'ц'),
  ('ФМИ', '404', 4, 1, 30, 'н', 'д', 'н', 'ц'),
  ('ФМИ', '405', 4, 1, 30, 'н', 'д', 'н', 'ц'),
  
  ('ФМИ', '500', 5, 2, 56, 'н', 'д', 'д', 'ц'),
  ('ФМИ', '501', 5, 1, 15, 'н', 'д', 'н', 'л'),
  ('ФМИ', '504', 5, 1, 18, 'н', 'д', 'н', 'л'),
  ('ФМИ', '512', 5, 1, 18, 'н', 'д', 'н', 'ц'),
  ('ФМИ', '513', 5, 1, 7, 'н', 'д', 'н', 'ц'),
  ('ФМИ', '514', 5, 1, 18, 'н', 'д', 'н', 'ц'),
  ('ФМИ', '526', 5, 1, 24, 'н', 'д', 'д', 'л');


INSERT INTO rooms VALUES
('ФМИ','312',3 ,4, 1, 1,'н','н','д' );

INSERT INTO rooms VALUES
('ФМИ','402',4 ,4, 1, 1,'н','н','ц');

 INSERT INTO subjects
  VALUES
  ('ДИС', 'л', 'Н. Рибарска', 4, 'н', 'б', 'н'),
  ('ДИС', 'с', 'Е. Недялков', 4, 'н', 'б', 'б'),
  ('ДИС', 'с', 'С. Апостолов', 4, 'н', 'б', 'б'),
  ('Алгебра', 'л', 'А. Каспарян', 3, 'н', 'н', 'б'),
  ('Алгебра', 'с', 'В. Дончев', 2, 'н', 'б', 'б'),
  ('ИО', 'л', 'Н. Златева', 2, 'н', 'д', 'н'),
  ('ИО', 'с', 'А. Попова', 2, 'н', 'д', 'н'),
  ('ИО', 'п', 'М. Хамамджиев', 2, 'д', 'б', 'д'),
  ('ООП', 'л', 'К. Георгиев', 3, 'н', 'б', 'д'),
  ('ООП', 'с', 'П. Ангелов', 2, 'д', 'б', 'д'),
  ('ООП', 'с', 'К. Петрова', 2, 'д', 'б', 'д'),
  ('ООП', 'п', 'К. Петрова', 2, 'д', 'б', 'д'),
  ('ООП', 'п', 'И. Иванова', 2, 'д', 'б', 'д');

 INSERT INTO studentsGroups
  VALUES
  ('КН', 1, 1, 1, 27),
  ('КН', 1, 2, 1, 25),
  ('КН', 1, 3, 1, 25),
  ('КН', 1, 4, 1, 28),
  ('КН', 2, 5, 1, 27),
  ('КН', 2, 6, 1, 29),
  ('КН', 2, 7, 1, 25),
  ('КН', 2, 8, 1, 26),
  ('КН', 1, 1, 3, 21),
  ('КН', 1, 2, 3, 20),
  ('КН', 1, 3, 3, 23),
  ('КН', 2, 5, 3, 21),
  ('КН', 2, 6, 3, 14),
  ('КН', 2, 7, 3, 23);


  INSERT INTO roomTaken  
  VALUES
  ('ФМИ', '302', 3, 'Алгебра', 'с', 'В. Дончев', 'КН', 3, 1, '2021-01-11 13:00:00', 2),
  ('ФМИ', '306', 3, 'ООП', 'с', 'К. Петрова', 'КН', 6, 1, '2021-01-11 13:00:00', 2),
  ('ФМИ', '200', 2, 'ДИС', 'л', 'Н. Рибарска', 'КН', 5, 1, '2021-01-11 14:00:00', 3),
  ('ФМИ', '200', 2, 'ДИС', 'л', 'Н. Рибарска', 'КН', 6, 1, '2021-01-11 14:00:00', 3),
  ('ФМИ', '200', 2, 'ДИС', 'л', 'Н. Рибарска', 'КН', 7, 1, '2021-01-11 14:00:00', 3);

