using System;
using System.Collections.Generic;
using System.Data.Entity;
using System.Linq;
using System.Web;

namespace Medical_teleconference.Models
{
    public class TeleconferenceDbContext : DbContext
    {
        public DbSet<User> Users { get; set; }
        public DbSet<Room> Rooms { get; set; }
        public DbSet<Comment> Comments { get; set; }
        public DbSet<Photo> Photos { get; set; }
        public DbSet<PhotoComment> PhotoComments { get; set; }
        
        public TeleconferenceDbContext() : base("DefaultConnection") { }
    }
}