Task Management System

*User can manage Tasks*

    -Create User

    -User can Log in app with email and tel (02 modes)
    
    -Logged User can manage Tasks and sub-tasks.crud tasks and sub tasks


*Rules* :

    1. if task is deleted, auto delete all sub tasks

    2. if all sub tasks are mark finished, parent task auto finish

    3. if parent task is mark as finish , all sub tasks auto finish


    -We can persist it by files system and db
*User Requirements*

    -userId*: string
    -firstName*: string
    -lastName*: string
    -email*: string (valid email address)
    -password*: string (must be strong password)
    -tel*: string (must be valid cmr tel number)

*Tasks Requirements* <br>
    
    -taskId*: string
    -title*: string
    -description: string
    -state: enum [pending,end]

