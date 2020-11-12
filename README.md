# E-space

Mobile application E-space gives the owner of an electric car an ability to locate a charger on a map easily and conveniently, find the directions to it and also be capable of making a payment by a credit card,

### Installation


Install the dependencies and devDependencies and start the server.

We use this command to bring the information from the old database and write it in the new one.

```sh
$ php artisan command:InsertData
```
Specifically the tables:
 - Users,
 - User cards,
 - Tags,
 - Chargers,
 - Charger Connector Types,
 - Orders,
 - Payments,
 - Country phone codes
 - etc.

### Documentations and other tools



| Title | Link |
| ------ | ------ |
| Swagger | [https://app.swaggerhub.com/apis/redberry/for_e_space/1.0.0] |
| Documentation | [https://docs.google.com/document/d/1oQF4duytnVRuaSX56NOWLwgbSUQbPonNq12ifooNcKg/edit] |
| Draw io | [https://www.draw.io/#G1YpFxD_5vZC8UihQe9sTbBau6uOXAU0vA] |

### Todos

 - Write MORE Tests
 - Add Night Mode
 
### Credits
 - Imeda Aivazashvili
 

License
----

MIT