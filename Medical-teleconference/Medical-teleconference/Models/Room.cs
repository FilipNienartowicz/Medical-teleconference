using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Data.Entity;
using System.Linq;
using System.Web;

namespace Medical_teleconference.Models
{
    [Table("Rooms")]
    public class Room : DbContext
    {
        [Key]
        [DatabaseGeneratedAttribute(DatabaseGeneratedOption.Identity)]
        public int RoomId { get; set; }
        [StringLength(50, ErrorMessage = "Maximal length of the roomname is 50 characters!")]
        public string RoomName { get; set; }
    }
}