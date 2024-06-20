For the Programmer candidate please follow this instructions:

### Create 1 Form page, 1 List page and 1 Statistic page that implement CRUD operations. ### 
**List Page**
1) List page is the Master List that show all record of user.
2) Create (Add User) Button to redirect to the form page on top of Master List.
3) Create Action column in Master List with Edit and Delete buttons.
4) Display only necessary fields for each row.
5) The list need to have search bar and can be filter by Phone Brand.

**Form Page**
1) The form page should have fields for Name, IC Number, Age, Email, Phone Number, Phone Brand and Phone Model.
2) Age will automatically calculate when IC Number is been key in.
3) User may have more than 1 Phone Number.
4) Don't show all list of Phone Model. Only show the Phone Model that related to the Phone Brand when it is selected. 
5) User can click "Submit" button to add data into the database or "Back" Button in the list page.

**Statistic Page**
1) The Statistic page should have Bar Chart that shows amount of users based on their Phone Brand. (Chart js)

**Additional file**
1) Create header page that have Navbar that can navigate between each page listed above and need to be included in all page.
2) Create footer page that have footer information about the website such as copyright etc and need to be included in all page.
3) This Additional page must store in inc folder.

#### Code Example ####

*Use inc/config.php file to create Database connection.
*Only brand and model table are given in assessment.sql

Additional Marks:
1) Add Pagination on List Page
2) Form page should have common validation for each fields. 
3) Please consider to sanitize the data.
4) Send before due date is preferable.
