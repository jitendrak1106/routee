# routee

As a User I want a mechanism capable to examine whether data and depending

on the temperature send an sms message to a specified number.

 

Use Weather Api from https://openweathermap.org/  to access current weather data for the Thessaloniki.

 

API documentation https://openweathermap.org/api

 

Use the endpoint api.openweathermap.org for your API calls

For the api calls use the key

b385aa7d4e568152288b3c9f5c2458a5

 

If the temperature is greater than 20C send SMS message to +30 6978745957 with text "Your name and Temperature more than 20C. <the actual temperature>"

else send sms message to +30  6978745957 with text "Your name and Temperature less than 20C. <the actual temperature>"

where <the actual temperature> the temperature that the weather api returns for Thessaloniki.

 

In order to send the SMS use the Routee API https://docs.routee.net/docs/

Use vanilla PHP
Use object-oriented programming
Use SOLID design principles
The code should be well documented.
Use defensive programming approach.
Target PHP version 7.2
Use Strict checking in all methods. 
 

Application ID for Routee API:

5f9138288b71de3617a87cd3

Application secret:  RSj69jLowJ 
