```mermaid

graph TB
    subgraph "Server Root Directory"
        subgraph "Global Scope (script1.php)"
            G1["Global Variables<br/>$globalVar = 'hello'<br/>$counter = 0"]

            subgraph "Function Scope 1"
                L1["Local Variables<br/>$localVar = 'world'<br/>Parameters: $param1"]
                L1 -.-> G1
                L1 -.- G1
            end

            subgraph "Function Scope 2"
                L2["Local Variables<br/>$temp = 123<br/>Parameters: $data"]
                L2 -.-> G1
                L2 -.- G1
            end
        end

        subgraph "Global Scope (script2.php)"
            G2["Global Variables<br/>$anotherVar = 'test'<br/>$config = array()"]

            subgraph "Function Scope 3"
                L3["Local Variables<br/>$result = null<br/>Parameters: $input"]
                L3 -.-> G2
                L3 -.- G2
            end
        end

        subgraph "Superglobal Scope"
            SG["Superglobals<br/>$_SESSION['user_id']<br/>$_COOKIE['theme']<br/>$_GET, $_POST<br/>$_SERVER, $_ENV<br/>$GLOBALS"]
        end
    end

    SG --> G1
    SG --> G2
    SG --> L1
    SG --> L2
    SG --> L3

    G1 -.x G2

    classDef globalScope fill:#e1f5fe,stroke:#01579b,stroke-width:2px
    classDef localScope fill:#f3e5f5,stroke:#4a148c,stroke-width:2px
    classDef superScope fill:#e8f5e8,stroke:#1b5e20,stroke-width:3px

    class G1 globalScope
    class G2 globalScope
    class L1 localScope
    class L2 localScope
    class L3 localScope
    class SG superScope
```
