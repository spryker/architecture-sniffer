# Project Architecture Sniffer Rules

The project ruleset introduces `13` new rules dedicated to project (`src/Pyz/`) application code.
Twelve of them live under `src/Project/<Layer>/` in the `ArchitectureSniffer\Project\` namespace
and extend `ArchitectureSniffer\SprykerAbstractRule`. The thirteenth — the Glue
`DependencyProviderMethodNameRule` — was folded into the core per-layer
`DependencyProviderMethodNameRule` family instead of being duplicated: it lives at
`src/Glue/DependencyProvider/DependencyProviderMethodNameRule.php` in the
`ArchitectureSniffer\Glue\DependencyProvider` namespace (completing the Yves/Client/Service/Zed
family) and is referenced by the project Glue ruleset. The rules are grouped by layer below.

Total: **13** new project rules (Common 7, Zed 4, Client 1, Glue 1 — the Glue rule in the core
`ArchitectureSniffer\Glue\DependencyProvider` namespace, the other 12 under `ArchitectureSniffer\Project\`).

## Common rules (7)
|                     Rule                      | Prio |                                                              Description                                                              | Info |
|:---------------------------------------------:|:----:|:-----------------------------------------------------------------------------------------------------------------------------------:|:----:|
| InstanceResolvingRule                         |  1   | Repository, EntityManager, QueryContainer, Facade, DependencyProvider, Client, Service instances can not be initialized directly with `new`. Use Dependency Provider and Resolvers. | |
| LayerAccessRule                               |  2   | Enforces project-level layer-boundary rules restricting which layers may call which other layers.                                   |      |
| LocatorInDependencyProviderOnlyRule           |  2   | Locator should be used in Dependency Provider only.                                                                                 |      |
| SingletonInstanceInDependencyProviderOnlyRule |  2   | Singleton getInstance() initialisation should be in Dependency Provider only.                                                       |      |
| ProjectNoBridgeRule                           |  3   | Project should not use and depend on the Bridge pattern.                                                                            |      |
| TooManyPublicMethodsRule                      |  2   | Too many public methods.                                                                                                            | Threshold = 8 public methods; ignores set/get/add/create/provide/is/has and *Action methods; ignores Facade, Stub, Client, Service and all Persistence classes. |
| WeirdModuleNameRule                           |  2   | Module name should not contain weird words like test, dummy, example, antelope.                                                     |      |

## Zed rules (4)
|                     Rule                     | Prio |                                                                      Description                                                                      | Info |
|:--------------------------------------------:|:----:|:---------------------------------------------------------------------------------------------------------------------------------------------------:|:----:|
| OrmAccessRule                                |  2   | No call from Orm Query to Zed Business, no call from Orm Entity to Zed Business, no call from Orm Query to Zed Communication.                          |      |
| OrmNewEntityNotInCommunicationRule           |  2   | Orm Entity can not be initialized in Zed Communication. Use Entity Manager.                                                                          |      |
| RepositoryReadOnlyRule                       |  2   | Repository should not perform save, update, delete DB operations.                                                                                   |      |
| RestrictedOrmQueryAccessInZedPersistenceRule |  2   | Access to the Orm Query in Zed persistence is possible only through the Repository, Entity Manager or Query Container.                               |      |

## Client rules (1)
|                  Rule                  | Prio |                          Description                         | Info |
|:--------------------------------------:|:----:|:------------------------------------------------------------:|:----:|
| UnusedZedRequestInSearchAndStorageRule |  1   | There should be no Zed Request in Search and Storage Client. |      |

## Glue rules (1)
|                Rule                  | Prio |                                 Description                                 | Info |
|:------------------------------------:|:----:|:---------------------------------------------------------------------------:|:----:|
| GlueDependencyProviderMethodNameRule |  3   | DependencyProvider should only contain additional add*() or get*() methods. | Class `ArchitectureSniffer\Glue\DependencyProvider\DependencyProviderMethodNameRule` (core Glue family, not `Project\`). |
