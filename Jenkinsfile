pipeline {
    agent any
    stages {
        stage("install php dependencies") {
            steps {
                sh 'composer install -n --no-ansi'
            }
        }
    }
    post {
        cleanup {
            echo "--- cleanup ---"
            echo sh(returnStdout: true, script: 'env')
        }
        success {
            echo "--- Pipeline Success ---"
        }
        failure {
            echo "--- Unfortunately it failed ---"
        }
    }
}
