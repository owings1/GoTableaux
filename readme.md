GoTableaux
==========

About
-----

### Description

A multi-logic proof generator. Evaluates arguments in several logics, 
and generates [analytic tableaux](http://en.wikipedia.org/wiki/Method_of_analytic_tableaux) 
proofs in JavaScript (using [Processing.js](http://processingjs.org/)), 
LaTeX format (using [Qtree](http://www.ling.upenn.edu/advice/latex/qtree/)), and JSON data.

The logics currently implemented include propositional classical and non-classical propositional logics:

- Classical Propositional Logic (CPL)
- First Degree Entailment (FDE)
- Logic of Paradox (LP)
- Strong Kleene (K3)
- Łukasiewicz
- GO
- Normal Modal Logic (K)
- T
- Deontic Logic (D)

### History

This project originated from an auxiliary project for my dissertation, 
in which I develop a many-valued propositional logic (which I call GO), 
and explore its applications. This software began as a tableaux proof 
generator for GO, so I could quickly generate LaTeX proofs for any argument.
Since framework was abstracted from the particulars of GO, I later 
decided to expand the program by implementing the tableaux rules for other
logics. 


### Status

In addition to starting to work on a web interface, I am expanding the 
framework to accommodate new logics. Current plans include:

- Weak Kleene
- Bochvar
- Modal FDE
- Modal GO

Links
-----
GitHub project page: 

[https://github.com/owings1/GoTableaux](https://github.com/owings1/GoTableaux)

Web UI:

[http://logic.dougowings.net/](http://logic.dougowings.net/)

The generated API documentation is available here:

[http://logic.dougowings.net/doc](http://logic.dougowings.net/doc)

License
-------
GoTableaux is released under Version 3 of the GNU Affero General Public License. See LICENSE file for the full text.

The example web interface uses [CakePHP](http://www.cakephp.org), which is released under the MIT license. See readme.md in the www directory for more information.

GoTableaux uses [Simple Test](http://simpletest.org), which is released under the GNU Lesser General Public License v2.1.

The [API Documentation](http://logic.dougowings.net/doc) is generated using [PhpDocumentor 2](http://www.phpdoc.org/), which is released under the MIT license.