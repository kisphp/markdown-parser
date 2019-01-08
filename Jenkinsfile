pipeline {
    agent any
    stages {
        stage("install php dependencies") {
            steps {
                sh 'composer install -n --no-ansi'
            }
        }
        stage("run tests"){
            steps {
                sh 'vendor/bin/phpunit --coverage-clover clover.xml'
            }
        }
        stage("display test coverage") {
            steps {
                step([
                    $class: 'CloverPublisher',
                    cloverReportDir: "`pwd`/",
                    cloverReportFileName: 'clover.xml',
                    healthyTarget: [methodCoverage: 70, conditionalCoverage: 80, statementCoverage: 80], // optional, default is: method=70, conditional=80, statement=80
                    unhealthyTarget: [methodCoverage: 50, conditionalCoverage: 50, statementCoverage: 50], // optional, default is none
                    failingTarget: [methodCoverage: 0, conditionalCoverage: 0, statementCoverage: 0]     // optional, default is none
                ])
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
