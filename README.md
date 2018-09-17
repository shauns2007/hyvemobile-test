
Coding Task: Attached you will find a CSV data dump. Please do the following with it:
* Import into a MySQL database.
* Save the date and time in a single time zone, but allow us to query the local date and time of the customer's transaction, should we want to.
* Validate that the email address’s domain is valid and write the IP address of that domain into another column.
* Create a “contact card” for each entry. The contact card is a JPEG image with the first and last name, as well as the email address.
* Once imported, the data should be posted to a remote url (i.e. example.org) including the generated image. (raw post, json)
* Create a JSON API with two endpoints: “contacts” and “timezones”. 
** The “GET /contacts.php" endpoint should include all records with pagination. The “GET /timezones.php?timezone={timezone}” endpoint should display a timezone field, total_contacts field and a special “contacts” field which will include the same information as per the “contacts” endpoint, filtered by the respective timezone.
* Document your program. Explain what it does, why and any technical nuances that you think requires explanation.

Notes:
The import script should be duplicate run safe.
No framework shall be used.
Any composer package may be used.
Any open source software may be used / it is encouraged to use the best tool for the job here.
Adhere to SOLID principles as much as possible.
The CSV file could have a very large amount of data, so write the application in such a way that multiple application servers could be used to process the work. The importing and processing should be well optimised to work in a multi server environment, i.e. delegate work where possible.
You don’t need to worry about HTTP routers etc for handling API requests, the requests can be served on the PHP files directly, but re-use code where possible.

What we are looking for:
* Speed
* Readability
* Testability
* Scalability

