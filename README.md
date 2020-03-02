## Google Translated Task Details:

You need to create a task application.
Tasks consist of:
- username;
- e-mail;
- task text;

Start page - a list of tasks with the ability to sort by user name, email and status. The conclusion of tasks needs to be done in pages of 3 pieces (with pagination). Any visitor without authorization can see the list of tasks and create new ones.

Log in to the administrator (login "admin", password "123"). The administrator has the ability to edit the text of the task and put a tick on the completion. Completed tasks in the general list are displayed with the corresponding mark "edited by the administrator".

In the application, you need to implement the MVC model using pure PHP. PHP frameworks cannot be used, libraries are possible. This application does not need a complex architecture, solve the tasks with the minimum necessary amount of code. Layout on bootstrap, there are no special requirements for design.

The result needs to be deployed on any free hosting (for example - zzz.com.ua) so that you can watch it in action. The code can be uploaded to github or bitbucket.

For your convenience, I provide a test protocol by which the test task is checked.
- Go to the start page of the application. A list of tasks should appear. The list contains fields: username, email, task text, status. There should be no typos. The gaps must be even. Nothing creeps. It should be possible to create a new task. There should be a button for authorization.
- Do not fill in the fields for the new task. Save task. Validation errors should be displayed. Enter “test” in the email field. There should be an error that the email is not valid.
- Create a task with the correct data (name “test”, email “test@test.com”, text “test job”). The task should appear in the task list. The data must be exactly the ones that were entered in the form field. After creating the task, a notification about the success of the addition should be displayed (feedback).
- To check the XSS vulnerability, you need to create a task with tags in the task description (add the text <script> alert (‘test’); </script> in the task description field, fill in the remaining fields). The task should appear in the list of tasks, while alert should not pop up with the text ‘test’.
- Create 2 more tasks. A new page in the pagination should appear.
- Sort the list by “username” field in ascending order. The list should be re-sorted. Go to the last page in pagination. Sorting should not go astray, tasks from the last page should be displayed. Then sort by the same field, but in descending order. Go to the first page. The username that was last on the list should be the first. Perform this test for the “email” and “status” fields.
- Go to the user authorization page. Try to login with empty fields. An error should be displayed that the fields are required or that the entered data is incorrect. Enter in the field for the username “admin1”, in the field for the password “321”. There should be an error about the wrong access details. Admin access should not be granted. Enter the data “admin” in the field for the name and “123” in the field for the password. Authorization must succeed. A button should be displayed to exit the admin profile.
- For the created task, put a mark “completed”. Reload page.
Edit the task text. Save and reload the page. The text of the task should be the one entered during editing. In the general list, the task should already be displayed with two marks: “completed” and “edited by the administrator”. The mark “edited by the administrator” should appear only if the text in the body of the task changes.
- In the general list, the task should be displayed with the status of the task “completed”.
- Open the application in parallel in a new tab. Log out in a new tab. In this tab, it should not be possible to edit the task. Return to the previous tab. Edit the task and save. The edited task should not be saved. The application must request authorization.
