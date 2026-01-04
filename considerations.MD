# Considerations
1. At first, I wanted to use a date variable but this makes it more difficult and less reliable when extracting data, so I'm requesting a day, month and year.
2. Is the rate limiting per server or per user? ANSWER: It is server wide so I will need to add the user ID into the key.
3. Do I want cache on the historical rates? ANSWER: I don't think so. The API request for a specific date/currency will only ever happen once because of the lock and the fact that they will be stored in my database so caching may just add complexity. Will review down the line.
4. There is an error in the historical rates controller so adding better error logging to debug. FIXED: After reviewing the logs I realised I never ran PHP artisan migrate so the tables didn't exist...
