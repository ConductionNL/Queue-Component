Queue Component
-------
The Queue Component contains a list of tasks to be performed later,
i.e. tasks can be created in this component that must be performed by other components at a later time.
This is similar to sending notifications with a few deviations:

* The notification always goes back to the submitting application
* The notification will be send at a later time

This logic is used to prevent cronjobs and analysis of the database,
for example: say a user places an order and has the possibility to pay later, if the user doesn't then the order will be canceled in 30 days.
If we wanted to fix this with a cronjob we would have to make a daily request of all orders that meet those requirements and cancel them.


This has a number of drawbacks
* We always have to analyze an entire database, even if we only need one order
* We are accurate to the day instead of to the minute (and if we want to be accurate to the minute, we have to query the entire database every minute)
* An increase in business logic, conditions and consequences (for example, do we want to send a reminder email 5 days in advance?) Leads to a sharp increase in database queries

 To prevent this we want to make a targeted request that goes off once every 30 days, this request can be created when an order is placed.
 Because containers can change in the meantime, and we want to be able to centrally manage this queue, we bundle the questions into a separate component. This is the Queue Component.
 
 The basic principle here is that the queue notifies another component in a targeted manner
 (with the information that can be used specifically for action, for example an order id),
 but that the handling application itself checks whether the action is still necessary as part of the execution (i.e. the order is still unpaid).
 
 In this way, the queue remains free from business logic, but combined with the Processes Component (PC) it offers the option to queue microservices and BPMN processes,
 whereby it is of course technically possible to delay the execution by 0 minutes. It makes it possible to run processes asynchronously.
