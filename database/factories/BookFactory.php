<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        $books = [
            ['To Kill a Mockingbird', 'Harper Lee'],
            ['1984', 'George Orwell'],
            ['Pride and Prejudice', 'Jane Austen'],
            ['The Great Gatsby', 'F. Scott Fitzgerald'],
            ['Crime and Punishment', 'Fyodor Dostoevsky'],
            ['Brave New World', 'Aldous Huxley'],
            ['The Catcher in the Rye', 'J.D. Salinger'],
            ['Of Mice and Men', 'John Steinbeck'],
            ['The Old Man and the Sea', 'Ernest Hemingway'],
            ['Fahrenheit 451', 'Ray Bradbury'],
            ['Dune', 'Frank Herbert'],
            ["The Hitchhiker's Guide to the Galaxy", 'Douglas Adams'],
            ['Foundation', 'Isaac Asimov'],
            ['Neuromancer', 'William Gibson'],
            ["Ender's Game", 'Orson Scott Card'],
            ['The Left Hand of Darkness', 'Ursula K. Le Guin'],
            ['Do Androids Dream of Electric Sheep?', 'Philip K. Dick'],
            ['Snow Crash', 'Neal Stephenson'],
            ['The Martian', 'Andy Weir'],
            ['Hyperion', 'Dan Simmons'],
            ['The Road', 'Cormac McCarthy'],
            ['Never Let Me Go', 'Kazuo Ishiguro'],
            ['The Kite Runner', 'Khaled Hosseini'],
            ['A Little Life', 'Hanya Yanagihara'],
            ['Normal People', 'Sally Rooney'],
            ['The Goldfinch', 'Donna Tartt'],
            ['Eleanor Oliphant Is Completely Fine', 'Gail Honeyman'],
            ['Where the Crawdads Sing', 'Delia Owens'],
            ['The Secret History', 'Donna Tartt'],
            ['Conversations with Friends', 'Sally Rooney'],
            ['Beloved', 'Toni Morrison'],
            ['Middlemarch', 'George Eliot'],
            ['One Hundred Years of Solitude', 'Gabriel García Márquez'],
            ['Anna Karenina', 'Leo Tolstoy'],
            ['The Brothers Karamazov', 'Fyodor Dostoevsky'],
            ['Ulysses', 'James Joyce'],
            ['Mrs Dalloway', 'Virginia Woolf'],
            ['Moby-Dick', 'Herman Melville'],
            ['The Sound and the Fury', 'William Faulkner'],
            ['Their Eyes Were Watching God', 'Zora Neale Hurston'],
            ['Meditations', 'Marcus Aurelius'],
            ['Thus Spoke Zarathustra', 'Friedrich Nietzsche'],
            ['The Stranger', 'Albert Camus'],
            ['Nausea', 'Jean-Paul Sartre'],
            ["Man's Search for Meaning", 'Viktor E. Frankl'],
            ['The Myth of Sisyphus', 'Albert Camus'],
            ['Sapiens: A Brief History of Humankind', 'Yuval Noah Harari'],
            ['Thinking, Fast and Slow', 'Daniel Kahneman'],
            ['The Name of the Wind', 'Patrick Rothfuss'],
            ['Circe', 'Madeline Miller'],
        ];

        [$title, $author] = $this->faker->randomElement($books);
        $googleBooksId = $this->faker->unique()->bothify('??########');

        return [
            'google_books_id' => $googleBooksId,
            'title'           => $title,
            'author'          => $author,
            'cover_image_url' => "https://picsum.photos/seed/{$googleBooksId}/128/192",
            'description'     => $this->faker->paragraphs(3, true),
            'published_date'  => $this->faker->year(),
        ];
    }
}
