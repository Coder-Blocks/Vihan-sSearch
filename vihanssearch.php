<?php
$api_key = 'AIzaSyBUDxsOWr0zAsN3d_Q6HCMtoYVZ6-eQkaU';
$response = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = $_POST['question'] ?? '';
    
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $api_key;
    
    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $question ?: "Explain how AI works"]
                ]
            ]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $result = curl_exec($ch);
    curl_close($ch);

    $response_data = json_decode($result, true);
    $response = $response_data['candidates'][0]['content']['parts'][0]['text'] ?? 'Error processing response';
    
    header('Content-Type: application/json');
    echo json_encode(['response' => $response]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vihan's AI Search</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* Same CSS as original */
        :root {
            --google-blue: #1a73e8;
            --google-gray: #5f6368;
            --google-bg: #fff;
        }

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            background: #fff;
            min-height: 100vh;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
        }

        .logo {
            text-align: center;
            margin: 100px 0 40px;
            font-size: 4rem;
            font-weight: 500;
            color: var(--google-blue);
            letter-spacing: -2px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .logo span:nth-child(1) { color: #4285f4; }
        .logo span:nth-child(2) { color: #ea4335; }
        .logo span:nth-child(3) { color: #fbbc05; }
        .logo span:nth-child(4) { color: #4285f4; }
        .logo span:nth-child(5) { color: #34a853; }
        .logo span:nth-child(6) { color: #ea4335; }

        .search-container {
            border: 1px solid #dfe1e5;
            border-radius: 24px;
            padding: 0 20px;
            margin: 0 auto 30px;
            transition: all 0.3s;
            position: relative;
        }

        .search-container:hover, .search-container:focus-within {
            box-shadow: 0 1px 6px rgba(32,33,36,.28);
            border-color: rgba(223,225,229,0);
        }

        .input-wrapper {
            display: flex;
            align-items: center;
            height: 54px;
        }

        #questionInput {
            flex: 1;
            border: none;
            outline: none;
            font-size: 16px;
            padding: 0 15px;
            background: transparent;
        }

        .voice-search {
            background: none;
            border: none;
            padding: 10px;
            cursor: pointer;
            position: relative;
        }

        .voice-search svg {
            width: 24px;
            height: 24px;
            fill: var(--google-blue);
        }

        .buttons {
            text-align: center;
            margin-bottom: 30px;
        }

        button {
            background-color: #f8f9fa;
            border: 1px solid #f8f9fa;
            border-radius: 4px;
            color: var(--google-gray);
            font-size: 14px;
            padding: 0 16px;
            height: 36px;
            cursor: pointer;
            transition: all 0.3s;
        }

        button:hover {
            border: 1px solid #dadce0;
            box-shadow: 0 1px 1px rgba(0,0,0,.1);
        }

        .response-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: var(--google-bg);
            border-radius: 8px;
        }

        .result-item {
            margin-bottom: 30px;
        }

        .result-title {
            color: var(--google-blue);
            font-size: 20px;
            margin-bottom: 4px;
        }

        .result-snippet {
            color: var(--google-gray);
            line-height: 1.5;
            font-size: 14px;
        }

        .loading {
            display: none;
            text-align: center;
            margin: 40px 0;
        }

        .loader {
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--google-blue);
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .listening-animation {
            position: absolute;
            width: 40px;
            height: 40px;
            background: rgba(26,115,232,0.1);
            border-radius: 50%;
            animation: pulse 1.5s infinite;
            right: -8px;
            top: -8px;
        }

        @keyframes pulse {
            0% { transform: scale(0.8); opacity: 1; }
            100% { transform: scale(1.5); opacity: 0; }
        }

        .powered-by {
            text-align: center;
            color: var(--google-gray);
            font-size: 12px;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <span>V</span><span>i</span><span>h</span><span>a</span><span>n</span><span>'s</span>
        </div>
        
        <div class="search-container">
            <div class="input-wrapper">
                <input type="text" id="questionInput" placeholder="Ask Vihan's AI anything..." autofocus>
                <button class="voice-search" id="voiceButton">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm-1 1.93c-3.94-.49-7-3.85-7-7.93h2c0 3.31 2.69 6 6 6s6-2.69 6-6h2c0 4.08-3.06 7.44-7 7.93V19h4v2H8v-2h4v-3.07z"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="buttons">
            <button onclick="generateContent()">Vihan Search</button>
            <button onclick="clearSearch()">I'm Feeling Curious</button>
        </div>

        <div class="loading" id="loading">
            <div class="loader"></div>
        </div>

        <div class="response-container" id="responseContainer"></div>
        
        <div class="powered-by">Powered by Gemini AI</div>
    </div>

    <script>
        // Voice recognition setup
        const voiceButton = document.getElementById('voiceButton');
        const questionInput = document.getElementById('questionInput');
        let recognition;
        
        if ('webkitSpeechRecognition' in window) {
            recognition = new webkitSpeechRecognition();
            recognition.continuous = false;
            recognition.interimResults = false;

            recognition.onstart = () => {
                voiceButton.innerHTML += '<div class="listening-animation"></div>';
            };

            recognition.onresult = (event) => {
                const transcript = event.results[0][0].transcript;
                questionInput.value = transcript;
            };

            recognition.onerror = (event) => {
                console.error('Speech recognition error:', event.error);
            };

            recognition.onend = () => {
                voiceButton.querySelector('.listening-animation')?.remove();
            };
        }

        voiceButton.addEventListener('click', () => {
            if (recognition) {
                recognition.start();
            } else {
                alert('Speech recognition not supported in this browser');
            }
        });

        // Handle Enter key
        questionInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') generateContent();
        });

        async function generateContent() {
            const loading = document.getElementById('loading');
            const responseContainer = document.getElementById('responseContainer');
            
            loading.style.display = 'block';
            responseContainer.innerHTML = '';

            try {
                const response = await fetch('<?= $_SERVER['PHP_SELF'] ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `question=${encodeURIComponent(questionInput.value)}&action=generate`
                });

                if (!response.ok) throw new Error(`API Error: ${response.status}`);
                
                const data = await response.json();
                const formattedResponse = data.response.split('\n').map(paragraph => `
                    <div class="result-item">
                        <div class="result-snippet">${paragraph}</div>
                    </div>
                `).join('');

                responseContainer.innerHTML = formattedResponse;
                responseContainer.style.opacity = 0;
                setTimeout(() => responseContainer.style.opacity = 1, 50);

            } catch (error) {
                responseContainer.innerHTML = `
                    <div class="result-item" style="color: #ea4335">
                        Error: ${error.message}
                    </div>
                `;
            } finally {
                loading.style.display = 'none';
            }
        }

        function clearSearch() {
            questionInput.value = '';
            questionInput.focus();
        }

        // Initial search
        window.onload = generateContent;
    </script>
</body>
</html>