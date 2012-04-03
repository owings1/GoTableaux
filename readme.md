GoTableaux
==========

About
-----

### Description

	A multi-logic tableaux proof generator.

	Logics implemented:
	- Classical Propositional Logic (CPL)
	- First Degree Entailment (FDE)
	- Logic of Paradox (LP)
	- Strong Kleene (K3)
	- GO

### History

	This project originated from an auxiliary project for my dissertation, in which I develop a many-valued propositional logic (which I call GO), and explore its applications. 
	This software began as a tableaux proof generator for GO, so I could quickly generate LaTeX proofs for any argument.


### Status

	In addition to starting to work on a web interface, I am expanding the framework to accommodate new logics. Current plans include:
	- Łukasiewicz
	- Weak Kleene
	- Bochvar
	- Normal Modal Logic (K)
	- Modal GO
	
Links
-----
GitHub project page: 

>https://github.com/owings1/GoTableaux

The beginnings of a web interface are up. It can be viewed at:

>http://logic.dougowings.net/

License
-------
GoTableaux is released under Version 3 of the GNU Affero General Public License. See LICENSE file for the full text.

The example web interface uses [http://www.cakephp.org](CakePHP), which is released under the MIT license. See readme.md in the www directory for more information.

GoTableaux uses [http://simpletest.org](Simple Test), which is released under the GNU Lesser General Public License v2.1.