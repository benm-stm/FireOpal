-----------------
Context: 
This test suite check The user ADMIN rights
-----------------
Setting:

Host: USE
Project_ID: USE
Project_Name: USE

Service: USE
  * Document
  * File
  * Trackers
  * Subversion
  * Wiki
------------------
User: USE
  * LOGIN: disciplus_simplex PW:DSeevaT
  * membership: YES
  * admin rights: Document, File, Trackers, Subversion, Wiki
  * NON admin rights: Project
------------------
Dependecies: No

------------------
Tests: 

-----------------------------------------------------------------------
***** Test Titles **************|*********** Varibles ***************
-----------------------------------------------------------------------
Security_ProjectAdmin           |
-----------------------------------------------------------------------
Security_DocmanAdmin            |
-----------------------------------------------------------------------
Security_WikiAdmin              |
-----------------------------------------------------------------------
Security_FilesAdmin             |
-----------------------------------------------------------------------
Security_SubversionAdmin        |
-----------------------------------------------------------------------