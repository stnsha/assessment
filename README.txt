If develop using PHP Native follow this instructions:

### Create 1 form Page and 1 List Page that implement CRUD operations. ### 
**List Page**
1) List page is the Master List that show all record of user.
2) Create (Add User) Button to redirect to the form page on top of Master List.
3) Create Action column in Master List with Edit and Delete buttons.
4) Display only necessary fields for each row.

**Form Page**
1) The form page should have fields for Name, IC Number, Age, Email, Phone Number, Phone Brand and Phone Model.
2) Age will automatically calculate when IC Number is been key in.
3) User may have more than 1 Phone Number.
4) Don't show all list of Phone Model. Only show the Phone Model that related to the Phone Brand when it is selected. 
5) User can click "Submit" button to add data into the database or "Back" Button in the list page.


*Use inc/config.php file to create Database connection.
*Only brand and model table are given in assessment.sql

Additional Marks:
Add Pagination on List Page and Form page have common validation for each fields. 