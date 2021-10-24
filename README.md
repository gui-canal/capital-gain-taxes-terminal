Capital Gain Taxes Terminal
=============================
Capital Gain Taxes Terminal is a simple project that calculate taxes for stocks operations based on given Json Objects Array of operations, returning the calculated taxes for each operation.

## Technologies:
- PHP
- Laravel Zero Framework (PHP terminal use);

###Dependencies
This project needs a 7.4 or higher PHP installation
```injectablephp
"php": "^7.4"
```
Usage
----
Inside of the project`s directory type the commands ***stocks*** and ***operate*** followed by your JSON array:
```
$ php stocks operate [YOUR JSON ARRAY OF OPERATIONS]
```
Example
------
If you type a correct Json Array, the application must return the tax for each operation:
```
$ php stocks operate '[{"operation":"buy", "unit-cost":10, "quantity": 10000}, {"operation":"sell", "unit-cost":20, "quantity": 5000}]'
> [{"tax":0},{"tax":10000}]
```
Contact
------
if you need any further information please contact me on my personal email:
```
gtkanal@gmail.com
```

