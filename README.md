# CRUD-API
#Aggregator of CRUD application services for conducting dummy surveys with subsequent access via API To access the API through Postman, you need:
- Register on the site.
- Log in to Postman(go to local Postman(desctop), create a new collection.
- Enter the site address, for example:bhttp://pandateam.local/api/login
- In the body, foorm-data tab  of the request, enter the email keys, in the column value: your registered email,enter the password key,in the column value: your password, 
  click the Send button.
- In the bottom tab Body, get a response in json format, if the authorization went through without errors, get a token, save the token.
- Create a new request in Postman, specifying the address, for example:http://pandateam.local/api/surveys,  go to the Authorization tab, 
- select Bearer Token from the dropdown list, paste your saved token and click submit.
- At the bottom of the Response body, get a random entry in json format
