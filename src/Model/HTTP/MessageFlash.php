<?php

namespace App\Feurum\Model\HTTP;

enum MESSAGE_TYPE {
    case TYPE_SUCCESS;
    case TYPE_INFO;
    case TYPE_WARNING;
    case TYPE_DANGER;
}

class MessageFlash {

    // Les messages sont enregistrés en session associée à la clé suivante 
    private static string $cleFlash = "_messagesFlash";


    private static function TypeToString(MESSAGE_TYPE $type): string {
        switch ($type) {
            case MESSAGE_TYPE::TYPE_SUCCESS:
                return "success";
            case MESSAGE_TYPE::TYPE_INFO:
                return "info";
            case MESSAGE_TYPE::TYPE_WARNING:
                return "warning";
            case MESSAGE_TYPE::TYPE_DANGER:
                return "danger";
        }
    }


    // $type parmi "success", "info", "warning" ou "danger" 
    public static function ajouter(MESSAGE_TYPE $type, string $message): void {


        $_SESSION[self::$cleFlash][] = ["type" => self::TypeToString($type), "message" => $message];
    }

    public static function contientMessage(string $type): bool {
        $messages = $_SESSION[self::$cleFlash] ?? [];
        foreach ($messages as $message) {
            if ($message["type"] == $type) {
                return true;
            }
        }
        return false;
    }

    // Attention : la lecture doit détruire le message
    public static function lireMessages(string $type): array {
        $messages = $_SESSION[self::$cleFlash] ?? [];
        $messagesRetour = [];
        foreach ($messages as $key => $message) {
            if ($message["type"] == $type) {
                $messagesRetour[] = $message["message"];
                unset($_SESSION[self::$cleFlash][$key]);
            }
        }
        return $messagesRetour;
    }

    public static function lireTousMessages(): array {
        $messages = $_SESSION[self::$cleFlash] ?? [];
        foreach ($messages as $key => $_) {
            unset($_SESSION[self::$cleFlash][$key]);
        }
        return $messages;
    }
}
