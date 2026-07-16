# Adapted PHPMD Rules

The project ruleset adapts `35` [PHPMD](https://phpmd.org/) built-in rules, grouped into the
6 categories below. Each rule is referenced from `src/Project/PhpMd/*.xml`. Configured property
values that differ from the PHPMD defaults are noted in the `Info` column.

Total: **35** adapted PHPMD rules (cleancode 7, codesize 4, controversial 6, design 7, naming 8, unusedcode 3).

## Clean Code Rules (7) [more](https://phpmd.org/rules/cleancode.html)
|          Rule         | Prio |                             Description                             | Info |
|:---------------------:|:----:|:-------------------------------------------------------------------:|:----:|
| UndefinedVariable     |  1   |  [link](https://phpmd.org/rules/cleancode.html#undefinedvariable)   |      |
| ElseExpression        |  3   |  [link](https://phpmd.org/rules/cleancode.html#elseexpression)      |      |
| IfStatementAssignment |  3   |  [link](https://phpmd.org/rules/cleancode.html#ifstatementassignment) |    |
| DuplicatedArrayKey    |  3   |  [link](https://phpmd.org/rules/cleancode.html#duplicatedarraykey)  |      |
| ErrorControlOperator  |  3   |  [link](https://phpmd.org/rules/cleancode.html#errorcontroloperator) |     |
| MissingImport         |  3   |  [link](https://phpmd.org/rules/cleancode.html#missingimport)       |      |
| BooleanArgumentFlag   |  4   |  [link](https://phpmd.org/rules/cleancode.html#booleanargumentflag) |      |

## Code Size Rules (4) [more](https://phpmd.org/rules/codesize.html)
|           Rule           | Prio |                               Description                              |                            Info                           |
|:------------------------:|:----:|:----------------------------------------------------------------------:|:---------------------------------------------------------:|
| CyclomaticComplexity     |   3  | [link](https://phpmd.org/rules/codesize.html#cyclomaticcomplexity)     |                                                           |
| NPathComplexity          |   3  | [link](https://phpmd.org/rules/codesize.html#npathcomplexity)          |                                                           |
| ExcessiveClassComplexity |   3  | [link](https://phpmd.org/rules/codesize.html#excessiveclasscomplexity) | The maximum WMC tolerable for a class = 100 (50 default)  |
| ExcessiveParameterList   |   3  | [link](https://phpmd.org/rules/codesize.html#excessiveparameterlist)   | The parameter count reporting threshold = 15 (10 default) |

## Controversial Rules (6) [more](https://phpmd.org/rules/controversial.html)
|          Rule          | Prio |                                Description                               | Info |
|:----------------------:|:----:|:------------------------------------------------------------------------:|:----:|
| Superglobals           |   2  | [link](https://phpmd.org/rules/controversial.html#superglobals)           |     |
| CamelCaseClassName     |   3  | [link](https://phpmd.org/rules/controversial.html#camelcaseclassname)     |     |
| CamelCasePropertyName  |   3  | [link](https://phpmd.org/rules/controversial.html#camelcasepropertyname)  |     |
| CamelCaseMethodName    |   3  | [link](https://phpmd.org/rules/controversial.html#camelcasemethodname)    |     |
| CamelCaseParameterName |   3  | [link](https://phpmd.org/rules/controversial.html#camelcaseparametername) |     |
| CamelCaseVariableName  |   3  | [link](https://phpmd.org/rules/controversial.html#camelcasevariablename)  |     |

## Design Rules (7) [more](https://phpmd.org/rules/design.html)
|           Rule          | Prio |                             Description                            | Info |
|:-----------------------:|:----:|:------------------------------------------------------------------:|:----:|
| NumberOfChildren        |   3  | [link](https://phpmd.org/rules/design.html#numberofchildren)        |     |
| DepthOfInheritance      |   3  | [link](https://phpmd.org/rules/design.html#depthofinheritance)      |     |
| ExitExpression          |   4  | [link](https://phpmd.org/rules/design.html#exitexpression)          |     |
| EvalExpression          |   2  | [link](https://phpmd.org/rules/design.html#evalexpression)          |     |
| GotoStatement           |   4  | [link](https://phpmd.org/rules/design.html#gotostatement)           |     |
| DevelopmentCodeFragment |   2  | [link](https://phpmd.org/rules/design.html#developmentcodefragment) |     |
| EmptyCatchBlock         |   3  | [link](https://phpmd.org/rules/design.html#emptycatchblock)         |     |

## Naming Rules (8) [more](https://phpmd.org/rules/naming.html)
|                 Rule                | Prio |                                   Description                                   |            Info           |
|:-----------------------------------:|:----:|:-------------------------------------------------------------------------------:|:-------------------------:|
| LongClassName                       |   3  | [link](https://phpmd.org/rules/naming.html#longclassname)                       | Maximum = 80 (40 default) |
| ShortClassName                      |   3  | [link](https://phpmd.org/rules/naming.html#shortclassname)                      |                           |
| ShortVariable                       |   3  | [link](https://phpmd.org/rules/naming.html#shortvariable)                       |                           |
| LongVariable                        |   3  | [link](https://phpmd.org/rules/naming.html#longvariable)                        | Maximum = 60 (20 default) |
| ShortMethodName                     |   3  | [link](https://phpmd.org/rules/naming.html#shortmethodname)                     |                           |
| ConstructorWithNameAsEnclosingClass |   3  | [link](https://phpmd.org/rules/naming.html#constructorwithnameasenclosingclass) |                           |
| ConstantNamingConventions           |   3  | [link](https://phpmd.org/rules/naming.html#constantnamingconventions)           |                           |
| BooleanGetMethodName                |   4  | [link](https://phpmd.org/rules/naming.html#booleangetmethodname)                |                           |

## Unused Code Rules (3) [more](https://phpmd.org/rules/unusedcode.html)
|         Rule        | Prio |                              Description                              | Info |
|:-------------------:|:----:|:---------------------------------------------------------------------:|:----:|
| UnusedPrivateField  |   4  | [link](https://phpmd.org/rules/unusedcode.html#unusedprivatefield)    |      |
| UnusedPrivateMethod |   4  | [link](https://phpmd.org/rules/unusedcode.html#unusedprivatemethod)   |      |
| UnusedLocalVariable |   4  | [link](https://phpmd.org/rules/unusedcode.html#unusedlocalvariable)   |      |
