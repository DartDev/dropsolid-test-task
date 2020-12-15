**Example exercises to illustrate dependency injection and services**  
  
To install:  
  

 - clone the repo  
 - install the site using the dropsolid_example install
   profile

quick command:  
  
    drush si dropsolid_example --db-url=mysql://db_user:db_pass@localhost/dropsolid_dep_inj  

  
Simplest example of dependency injection  
  

    global $a = 5;  
    global $b = 5;  
      
    function sum() {  
        global $a;  
        global $b;  
        return $a + $b;  
    }  

  
Obviously stupid, right?   
But replace global $a with \\Drupal::service('a') and suddenly everyone thinks it's okay.  
  
At its core, dependency injection just means giving a function or method or class all the things it needs  
to do its job. It shouldn't have to reach outside of its scope to grab information. This way you can test it (by passing  
in a mock), you can re-use it (by passing in different variables), it's reliable (because it's not grabbing   
stuff outside its scope that may change because of other processes) and as an added bonus other developers won't try and   
hurt you.  
  
  
# Exercises:

- We've got a controller and block, both outputting roughly the same thing. Please refactor these so the call to fetch data happens in a service. Then, inject that service in both the controller and block instead of using \Drupal::service()
- Take over the MailManager service and ensure all mails are redirected to "nope@doesntexist.com"
- On the path /dropsolid/example/photos the breadcrumb should be Home > Dropsolid > Example > Photos
- Take over the LanguageManager service in a way that doesn't preclude others from also taking over the LanguageManager service