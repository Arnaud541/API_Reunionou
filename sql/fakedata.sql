INSERT INTO users (firstname, lastname, email, password)
VALUES
('Alice', 'Dupont', 'alice.dupont@example.com', 'password123'),
('Bob', 'Martin', 'bob.martin@example.com', 'password123'),
('Charlie', 'Durand', 'charlie.durand@example.com', 'password123'),
('David', 'Gagnon', 'david.gagnon@example.com', 'password123'),
('Emily', 'Leblanc', 'emily.leblanc@example.com', 'password123'),
('Frank', 'Roy', 'frank.roy@example.com', 'password123'),
('Grace', 'Tremblay', 'grace.tremblay@example.com', 'password123'),
('Henry', 'Lavoie', 'henry.lavoie@example.com', 'password123'),
('Isabella', 'Lapierre', 'isabella.lapierre@example.com', 'password123'),
('Jack', 'Bélanger', 'jack.belanger@example.com', 'password123');

INSERT INTO events (title, description, latitude, longitude, street, zipcode, city, organizer_id)
VALUES
    ('Soirée jeux de société', 'Venez passer une soirée conviviale autour de jeux de société', 48.856614, 2.3522219, '8 Rue de la Roquette', '75011', 'Paris', 1),
    ('Tournoi de pétanque', 'Inscrivez-vous pour participer au tournoi de pétanque annuel', 43.296482, 5.36978, '10 Avenue de la Pointe Rouge', '13008', 'Marseille', 2),
    ('Collecte de déchets', 'Participez à une collecte de déchets pour préserver notre environnement', 47.218371, -1.553621, '5 Rue de l''Étoile du Matin', '44600', 'Saint-Nazaire', 3);

INSERT INTO participants (event_id, user_id, firstname, lastname, email, status)
VALUES
    (1, 2, 'John', 'Doe', 'johndoe@example.com', 'accepted'),
    (1, 3, 'Jane', 'Doe', 'janedoe@example.com', 'accepted'),
    (1, 4, 'Bob', 'Smith', 'bobsmith@example.com', 'pending'),
    (2, 1, 'Alice', 'Smith', 'alicesmith@example.com', 'declined'),
    (2, 3, 'Jane', 'Doe', 'janedoe@example.com', 'accepted'),
    (3, 2, 'John', 'Doe', 'johndoe@example.com', 'accepted'),
    (3, 4, 'Bob', 'Smith', 'bobsmith@example.com', 'accepted');

INSERT INTO comments (event_id, user_id, content)
VALUES
    (1, 2, "Super événement, j'ai passé un excellent moment !"),
    (1, 3, "Merci pour l'organisation, tout était parfait."),
    (2, 1, "Dommage que je n'aie pas pu venir, j'aurais aimé participer."),
    (3, 4, "Très belle initiative, merci pour cette soirée."),
    (3, 2, "Je suis content d'avoir pu participer, merci à l'organisateur !");
