use futurepres;

-- Tables must be dropped in this order
DROP TABLE IF EXISTS questions;
DROP TABLE IF EXISTS upvotes;
DROP TABLE IF EXISTS eventlog;
DROP TABLE IF EXISTS parents;
DROP TABLE IF EXISTS pages;

--
-- Table structure for table 'pages'
--

CREATE TABLE pages (
  pageid int(11) NOT NULL AUTO_INCREMENT,
  name varchar(45) NOT NULL,
  path varchar(255) NOT NULL,
  PRIMARY KEY (pageid)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table 'eventlog'
--

CREATE TABLE eventlog (
  logid int(11) NOT NULL AUTO_INCREMENT,
  ip varchar(45) NOT NULL,
  pageid int(11) NOT NULL,
  dtime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (logid),
  CONSTRAINT page_restrict_eventlog FOREIGN KEY (pageid) REFERENCES pages (pageid) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table 'upvotes'
--

CREATE TABLE upvotes (
  upvoteid int(11) NOT NULL AUTO_INCREMENT,
  pageid int(11) NOT NULL,
  PRIMARY KEY (upvoteid),
  CONSTRAINT page_restrict_upvotes FOREIGN KEY (pageid) REFERENCES pages (pageid) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table 'parents'
--

CREATE TABLE parents (
  parentsid int(11) NOT NULL AUTO_INCREMENT,
  parent int(11) NOT NULL,
  child int(11) NOT NULL,
  PRIMARY KEY (parentsid),
  CONSTRAINT page_restrict_parent FOREIGN KEY (parent) REFERENCES pages (pageid) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT page_restrict_child FOREIGN KEY (child) REFERENCES pages (pageid) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table 'questions'
--

CREATE TABLE questions (
  questionsid int(11) NOT NULL AUTO_INCREMENT,
  pageid int(11) NOT NULL,
  question varchar(255) NOT NULL,
  answer tinyint(1) DEFAULT 0,
  PRIMARY KEY (questionsid),
  CONSTRAINT page_restrict_question FOREIGN KEY (pageid) REFERENCES pages (pageid) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;