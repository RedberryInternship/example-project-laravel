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

   [test]: <https://dsadas.ge/r>



//todo Vobi
1. როგორ ვნახე ლარაველის 6.2 ვერსიას ვიყენებთ, მიმდიანრე არის 8.0 რამდენად შეგვიძლია ახალ ვერსიაზე აწევა?
2. ასევე ვნახე 6.2 ფოლდერებბი სტრუქტურა https://laravel.com/docs/6.x/structure, მემგონი ცოტაოდენ ამცდარი ვართ ფოლდერებიბს სტრუქტურას და იქნებ დავვიცვათ ის სტანდრტი რაც მათ აქვთ. ასევე დირექტორიებბის აღწერა რიდმიში თუ სად რას ვწერთ მაგ: https://cln.sh/ywCqCX, ასევე როგორც ვნახე ბოლო ვერსიაში ფოლდერებისბ სტრუქტურა ბევრად კარგად არის დალაგებული
3. როგორც საწყის ეტაპზე მოვილაპრაკეთ ყველა მოდელზე სულ მცირე ტესტები უნდა იყოს და ის როუტები რომლებიც გვაქვს მაგათზე უნდა მოწმდებოდეს აბრუენბბენ თუ არა 200 სტუს სხვასახვა ქეისებში. ეხა როგორც ვნახე სრულად არ არის დაფარული რამოდენიმე არის დაწერილი
3. გვჭრიდება სერვერული ინფრასტტური დოკუმენტაცია თუ სად რა გვაქვს განლეგებული, რა ტულებს ვიყენებთ, რომელი სერვისი რომელ სერვეზეა გაშვებული. რა სერთიფიკატებს ვიყენებთ და ასე შემდეგ...
4. აღწერა გვჭირდება თითვული გამოყენებული ფრეიმვორიკის თუ რომელს ვიყენებეთ და რატომ მაგ: https://cln.sh/Di3mkd
5. პროექტი გაშვების აღწერა მაგ: https://cln.sh/eH2s33
6. ბაზის მიგრაციის დოკუმეტაცია, ბაზაში ცვლილებების შეტანის მომზადება და სკრიპტების გაშვების დოკუმენტაცია.წ
 მაგ: https://cln.sh/ImsfzI
7. სერვერზე დეპლოიმენტის დოკუემენტაცია, მაგ: https://cln.sh/ImsfzI, https://cln.sh/n33P17
8. ტესტების გაშვების რიდმი https://cln.sh/wQ25oO
9. რესუსრსები რამოდენიმე ადგილას გვაქვს და არ შეიზლებაბ რომ ერთი ადგილიდან გამოვიყენოთ ფროექტის სტრუქტურის შესანარჩუნებლად https://cln.sh/Rrfx4Z 
10. ეს როგორც ვხდები მოდელები არის ბაზის და არ შეგვიძლია სტუქტურულად რომ ერთ model ფოლდერში ჩავყაროთ https://cln.sh/2OSTZm ?? მაგრამ აქვე ვნახე ისეთი კლასები რომლებიც კავშირი არ აქვთ მოდელებთან და მგონი კარგად გადასახედი და დასალაგებებლი გვაქვს ეს ფოლდერის სტრუქტურები. გთხოვ კარგად გადახადე 2.ჩაგდებულ ფოლდრები სტრუქტურას.
11. ავღწეროთ პროდაქშენ გარემოს მისამართები, ასევე დეველოპმენთ გარემოს მისამართები
12.  რესტისთვის კარგი იქენბოდა რომ გენერიდებოდეს swagger დოკუმენტაცია.
13. https://cln.sh/vOT324 ეს ფაილი რაში გამოიყენება?
14. ამ ექსელის ფაილის რაიმეში ვიყენებთ? https://cln.sh/2BdTV3
15. 
