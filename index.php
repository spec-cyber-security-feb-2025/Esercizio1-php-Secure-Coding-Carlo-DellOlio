<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controllo Accessi - Esempio Sicurezza</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <style>
        .response-box {
            margin-top: 2rem;
            padding: 1.5rem;
            border-radius: 6px;
        }

        .response-box.public {
            background-color: #f5f5f5;
            border-left: 5px solid #3273dc;
        }

        .response-box.admin {
            background-color: #fff3f3;
            border-left: 5px solid #ff3860;
        }

        .response-box.error {
            background-color: #fff3e0;
            border-left: 5px solid #ff9800;
        }
    </style>
</head>

<body>
    <section class="section">
        <div class="container">
            <h1 class="title">🛡️ A01:2021 - Broken Access Control</h1>
            <h2 class="subtitle">Esempio di vulnerabilità e soluzione per il controllo degli accessi</h2>

            <!-- Form per testare le azioni -->
            <div class="box">
                <div class="buttons">
                    <a href="?action=getappInfo" class="button is-primary">Info Pubbliche</a>
                </div>
            </div>

            <!-- Risposta del server -->
            <div class="response-box <?php echo $action === 'getappInfo' ? 'public' : ($action === 'admin_getappInfo' ? 'admin' : 'error'); ?>">
                <?php
                session_start();

                // Riceve il parametro richiesto dall'utente
                $action = $_GET['action'] ?? '';

                //Codice SICURO
                //Simuliamo un utente autenticato (in un caso reale, sarebbe impostato dopo il login)

                $_SESSION['user_role'] = $_SESSION['user_role'] ?? 'guest';

                $action = $_GET['action'] ?? '';

                //✅ Permettiamo a tutti di vedere le informazioni pubbliche
                if ($action === 'getappInfo') {
                    echo json_encode(["info" => "Informazioni pubbliche sull'app"]);
                }
                // ✅ Proteggiamo l'API amministrativa verificando il ruolo
                elseif ($action === 'admin_getappInfo') {
                    if ($_SESSION['user_role'] !== 'admin') {
                        http_response_code(403);
                        die(json_encode(["error" => "Accesso negato"]));
                    }
                    echo json_encode(["info" => "Dati amministrativi sensibili"]);
                } else {
                    echo json_encode(["error" => "Azione non valida"]);
                }
                ?>
            </div>

            <!-- Sezione Soluzione Sicura -->
            <div class="box secure-box">
                <h3 class="title is-4">🔒 Soluzione Sicura</h3>
                <p>Per proteggere l'accesso ai dati amministrativi, è necessario verificare il ruolo dell'utente:</p>
                <pre>
if ($_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    die("Accesso negato");
}
                </pre>
            </div>
        </div>
    </section>
</body>

</html>